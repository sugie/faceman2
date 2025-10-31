### ゴール
`docs/bike_diagnosis_questionnaire.xlsx` に入っている Bike fit 設定データ（ジャンル、質問、選択肢、重み付け）を「マイグレーション実行時」に DB に投入できるようにします。

以下では、依存関係の最小化と再現性（Infrastructure as Code）を重視して、マイグレーションの中で Excel を読み込み、各テーブルに `upsert` する実装例を提示します。

---

### 前提（既存スキーマ）
すでに次のマイグレーションでスキーマは作成済みですね。
- `database\migrations\2025_10_26_000000_create_bike_diagnosis_schema.php`
  - `bf_genres`, `bf_questions`, `bf_options`, `bf_weights` など

投入対象は主に次の4テーブルです。
- `bf_genres(id, name)`
- `bf_questions(id, section, body, answer_type)`
- `bf_options(id, question_id, label)`
- `bf_weights(id, question_id, option_id, genre_id, score)`

---

### おすすめ構成
- 追加マイグレーションを1本作成（例: `2025_10_26_010000_seed_bike_diagnosis_from_excel.php`）。
- 依存ライブラリに `phpoffice/phpspreadsheet` を使用して `xlsx` を読み込みます。
- マイグレーション `up()` 内でトランザクション管理し、Sheetごとの投入を `upsert` で実施。
- 本番・CI ともに同じ Excel から deterministically にデータが入るようにします。

注意: マイグレーションの中で外部ライブラリを呼ぶことに抵抗がある場合は、Seeder 化して `Artisan::call('db:seed', ...)` を `up()` から呼び出す代替案もあります（後述）。ただし「マイグレーション時に設定したい」との要件上、今回はマイグレーション内で直接投入する例を示します。

---

### 1) 依存の追加
- `composer.json` に `phpoffice/phpspreadsheet` が必要です。
  - 例: `composer require phpoffice/phpspreadsheet:^2.0`
- Docker 開発環境で `composer install` が通るようにしておいてください。

---

### 2) Excel フォーマット（想定）
Excel には次の4枚のシートがある想定で例を示します。もし実ファイルが違う構成なら、列名を合わせて修正してください。

- Sheet: `genres`
  - A列: `id`（数値, 省略可。空なら連番自動採番で insert）
  - B列: `name`
- Sheet: `questions`
  - A列: `id`（数値, 省略可）
  - B列: `section`
  - C列: `body`
  - D列: `answer_type`（`single` or `multi`）
- Sheet: `options`
  - A列: `question_id`（数値 or 問題の `id`）
  - B列: `label`
  - C列: `option_id`（オプションに固定 ID を割り振りたい場合のみ。なければ空で OK）
- Sheet: `weights`
  - A列: `question_id`
  - B列: `option_label`（または `option_id` のどちらか）
  - C列: `genre_id`
  - D列: `score`（0–255 の想定）

補足:
- `option_label` で `bf_options` の `label` に突き合わせ、`option_id` を引く方式にしています。もし Excel に `option_id` が明記されていればそれを優先。

---

### 3) マイグレーション例（完全サンプル）
以下を新規マイグレーションに貼り付けて調整してください。`xlsx` のシート名・列構成が違う場合は読み取りパートを合わせます。

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // docs/bike_diagnosis_questionnaire.xlsx から読込
        $xlsxPath = base_path('docs\\bike_diagnosis_questionnaire.xlsx');
        if (! file_exists($xlsxPath)) {
            throw new RuntimeException("Excel not found: {$xlsxPath}");
        }

        // PhpSpreadsheet を使う
        $this->ensureSpreadsheetAvailable();
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($xlsxPath);

        DB::transaction(function () use ($spreadsheet) {
            // 1) genres
            if ($sheet = $this->getSheetByName($spreadsheet, 'genres')) {
                $rows = $sheet->toArray(null, true, true, true);
                // 1行目ヘッダー想定
                foreach (array_slice($rows, 1) as $row) {
                    $id   = $this->val($row['A']);
                    $name = $this->val($row['B']);
                    if (!$name) { continue; }

                    $payload = ['name' => $name];
                    if ($id) { $payload['id'] = (int)$id; }

                    // upsert by name（name は unique の想定）
                    DB::table('bf_genres')->updateOrInsert(
                        ['name' => $name],
                        $payload
                    );
                }
            }

            // 2) questions
            if ($sheet = $this->getSheetByName($spreadsheet, 'questions')) {
                $rows = $sheet->toArray(null, true, true, true);
                foreach (array_slice($rows, 1) as $row) {
                    $id          = $this->val($row['A']);
                    $section     = $this->val($row['B']);
                    $body        = $this->val($row['C']);
                    $answerType  = $this->val($row['D']) ?: 'single';
                    if (!$section || !$body) { continue; }

                    $unique = ['section' => $section, 'body' => $body];
                    $payload = [
                        'section' => $section,
                        'body' => $body,
                        'answer_type' => in_array($answerType, ['single','multi'], true) ? $answerType : 'single',
                    ];
                    if ($id) { $payload['id'] = (int)$id; }

                    DB::table('bf_questions')->updateOrInsert($unique, $payload);
                }
            }

            // 3) options（question_id に対して label のユニーク性を担保）
            // question の id は、section+body のユニークキーから引き直すこともできますが、
            // Excel に id がある前提でそのまま使うのが確実です。
            if ($sheet = $this->getSheetByName($spreadsheet, 'options')) {
                $rows = $sheet->toArray(null, true, true, true);
                foreach (array_slice($rows, 1) as $row) {
                    $questionId = $this->intvalOrNull($row['A']);
                    $label      = $this->val($row['B']);
                    $optionId   = $this->intvalOrNull($row['C']);
                    if (!$questionId || !$label) { continue; }

                    $unique = ['question_id' => $questionId, 'label' => $label];
                    $payload = ['question_id' => $questionId, 'label' => $label];
                    if ($optionId) { $payload['id'] = $optionId; }

                    DB::table('bf_options')->updateOrInsert($unique, $payload);
                }
            }

            // 4) weights
            if ($sheet = $this->getSheetByName($spreadsheet, 'weights')) {
                $rows = $sheet->toArray(null, true, true, true);
                foreach (array_slice($rows, 1) as $row) {
                    $questionId  = $this->intvalOrNull($row['A']);
                    $optionLabel = $this->val($row['B']);
                    $genreId     = $this->intvalOrNull($row['C']);
                    $score       = $this->intvalOrNull($row['D']);
                    if (!$questionId || !$genreId || $score === null) { continue; }

                    // option_id 取得: option_id 列があればそれを優先
                    $optionId = null;
                    if (isset($row['E'])) { // もし E列に option_id を用意している場合
                        $optionId = $this->intvalOrNull($row['E']);
                    }
                    if (!$optionId) {
                        if (!$optionLabel) { continue; }
                        $optionId = DB::table('bf_options')
                            ->where('question_id', $questionId)
                            ->where('label', $optionLabel)
                            ->value('id');
                    }
                    if (!$optionId) { continue; }

                    DB::table('bf_weights')->updateOrInsert(
                        ['question_id' => $questionId, 'option_id' => $optionId, 'genre_id' => $genreId],
                        ['score' => max(0, min(255, (int)$score))]
                    );
                }
            }
        });
    }

    public function down(): void
    {
        // データのみ巻き戻す場合（任意）。安全のため「この Excel ソースで入れた行っぽいもの」を削除。
        // ここでは簡易に全消し。必要に応じて条件付き削除に変えてください。
        DB::table('bf_weights')->delete();
        DB::table('bf_options')->delete();
        DB::table('bf_questions')->delete();
        DB::table('bf_genres')->delete();
    }

    private function ensureSpreadsheetAvailable(): void
    {
        if (!class_exists(\PhpOffice\PhpSpreadsheet\IOFactory::class)) {
            throw new RuntimeException('phpoffice/phpspreadsheet is required. Run composer require phpoffice/phpspreadsheet');
        }
    }

    private function getSheetByName(\PhpOffice\PhpSpreadsheet\Spreadsheet $ss, string $name): ?\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
    {
        $sheet = $ss->getSheetByName($name);
        return $sheet ?: null;
    }

    private function val($v): ?string
    {
        if ($v === null) return null;
        $s = trim((string)$v);
        return $s !== '' ? $s : null;
    }

    private function intvalOrNull($v): ?int
    {
        $s = $this->val($v);
        return $s === null ? null : (int)$s;
    }
};
```

ポイント:
- `updateOrInsert` を使って「再実行しても壊れない」ようにしています。
- `down()` では簡易に全削除にしています。必要に応じて「Excel に存在するキーのみ削除」などに変更してください。
- Excel の列・シート名が異なる場合は `getSheetByName()` や列アクセス（`A`,`B`...）を実ファイルに合わせて調整します。

---

### 代替案（Seeder をマイグレーションから呼ぶ）
- `database/seeders/BikeDiagnosisSeeder.php` を作り、上のロジックを Seeder に置きます。
- マイグレーション `up()` 内で `\Artisan::call('db:seed', ['--class' => BikeDiagnosisSeeder::class]);` を呼ぶ。
  - マイグレーションにアプリ層の依存が入るのを避けたい場合は、Seeder 側で `PhpSpreadsheet` を使うようにします。

---

### 動作確認チェックリスト
- [ ] `composer require phpoffice/phpspreadsheet` を実施し、Docker/CI で解決できること
- [ ] `docs/bike_diagnosis_questionnaire.xlsx` がリポジトリに同梱されていること（パスは `base_path('docs\\...')` で参照）
- [ ] マイグレーションを実行し、次の点を手元で確認:
  - [ ] `bf_genres` に想定の件数が入る
  - [ ] `bf_questions` にセクション・本文・回答タイプが正しく入る
  - [ ] `bf_options` に各質問の選択肢が入る
  - [ ] `bf_weights` のユニークキー `uq_weight` が壊れない（`question_id+option_id+genre_id`）

---

### トラブルシューティング
- エラー: `Excel not found` → 実ファイルのパス・ファイル名を確認（Windows でも `base_path('docs\\...')` でOK）
- エラー: `Class ... PhpSpreadsheet ... not found` → `composer require phpoffice/phpspreadsheet` 未実施
- 文字化けや全角・半角の差で選択肢突合に失敗 → Excel 側の `label` を DB 側と厳密一致させるか、正規化処理（trim, 全角半角変換）を `val()` に追加
- 同じ `label` が同一 `question_id` 内で重複 → Excel を修正するか、`options` のユニーク性ルールを再検討

---

### 追加でご確認したいこと
- 実際の Excel のシート構成・列見出しを共有いただければ、上記コードの対応箇所（列/シート名）をピンポイントで合わせ込みます。
- `bf_questions.id` や `bf_options.id` を Excel 側で固定したいか（移行再現性のため固定を推奨）

この方針で「マイグレーション実行時に Excel に基づく初期データ投入」を満たせます。必要があれば、実ファイルを前提にした最終版コードに落とし込みます。