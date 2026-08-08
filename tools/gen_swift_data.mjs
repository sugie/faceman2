// BikeFit: Laravel の seed migration と BikefitService から
// iOS 用の静的データ (BikeFitData.swift) を生成する。
//
//   node tools/gen_swift_data.mjs
//
// 問診票を変更したら migration を直してこれを再実行する。
// 手で Swift 側を編集しないこと。#GEN01

import { readFileSync, writeFileSync, mkdirSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const ROOT = join(dirname(fileURLToPath(import.meta.url)), '..');
const SEED_PATH = join(ROOT, 'database/migrations/2025_10_26_010000_seed_bikefit.php');
const SERVICE_PATH = join(ROOT, 'app/Services/Bikefit/BikefitService.php');
const OUT_PATH = join(ROOT, 'ios/BikeFitData.swift');

const seed = readFileSync(SEED_PATH, 'utf8');
const service = readFileSync(SERVICE_PATH, 'utf8');

// ── 抽出 ─────────────────────────────────────────────
// PHP の配列リテラルを正規表現で拾う。seed は機械生成された素直な形なので
// 本格的なパーサは不要。想定外の形が来たら件数チェックで落ちる。

const questions = [...seed.matchAll(
  /\['id' => (\d+), 'ono' => (\d+), 'section' => '([^']*)', 'body' => '([^']*)'/g
)].map(m => ({ id: +m[1], ono: +m[2], section: m[3], body: m[4] }));

const options = [...seed.matchAll(
  /\['id' => (\d+),\s*'sno' => (\d+), 'question_id' => (\d+), 'label' => '([^']*)'/g
)].map(m => ({ id: +m[1], sno: +m[2], questionId: +m[3], label: m[4] }));

const genres = [...seed.matchAll(
  /\['id' => (\d+), 'name' => '([^']*)'/g
)].map(m => ({ id: +m[1], name: m[2] }));

const weights = [...seed.matchAll(
  /\['question_id' => (\d+), 'option_id' => (\d+), 'genre_id' => (\d+), 'score' => (\d+)\]/g
)].map(m => ({ questionId: +m[1], optionId: +m[2], genreId: +m[3], score: +m[4] }));

// 診断結果文: case 8010: ... return "…";
const descriptions = new Map();
for (const m of service.matchAll(/case (\d+):\s*(?:\/\/[^\n]*)?\s*return\s+"((?:[^"\\]|\\.)*)";/g)) {
  descriptions.set(+m[1], m[2]);
}

// ── 検証 ─────────────────────────────────────────────
// 静的データなので、ここで落ちれば Swift 側は絶対に壊れない。
const expected = options.length * genres.length;
const problems = [];
if (questions.length === 0) problems.push('質問が 0 件');
if (options.length === 0) problems.push('選択肢が 0 件');
if (genres.length === 0) problems.push('ジャンルが 0 件');
if (weights.length !== expected) {
  problems.push(`重み ${weights.length} 件。期待値は ${options.length}×${genres.length}=${expected} 件`);
}
for (const g of genres) {
  if (!descriptions.has(g.id)) problems.push(`ジャンル ${g.id} (${g.name}) の診断結果文がない`);
}
for (const o of options) {
  if (!questions.some(q => q.id === o.questionId)) problems.push(`選択肢 ${o.id} の質問 ${o.questionId} がない`);
}
if (problems.length) {
  console.error('#GEN02: 抽出に失敗しました:\n  - ' + problems.join('\n  - '));
  process.exit(1);
}

// ── 重みマトリクス化 ────────────────────────────────
// option_id -> ジャンル順（genres の並び）のスコア配列。
const genreIndex = new Map(genres.map((g, i) => [g.id, i]));
const scoreMatrix = new Map(options.map(o => [o.id, new Array(genres.length).fill(0)]));
for (const w of weights) {
  const row = scoreMatrix.get(w.optionId);
  if (!row) { console.error(`#GEN03: 未知の option_id ${w.optionId}`); process.exit(1); }
  row[genreIndex.get(w.genreId)] = w.score;
}

// ── Swift 生成 ───────────────────────────────────────
const esc = s => s.replace(/\\/g, '\\\\').replace(/"/g, '\\"');

// PHP のダブルクォート文字列内の \n は実際の改行。Swift の複数行リテラルへ。
const escMultiline = s =>
  s.replace(/\\\\/g, '\\').replace(/\\"/g, '"').replace(/\\n/g, '\n');

const out = [];
out.push(`// BikeFitData.swift — 自動生成。手で編集しないこと。`);
out.push(`// 生成元: database/migrations/2025_10_26_010000_seed_bikefit.php`);
out.push(`//         app/Services/Bikefit/BikefitService.php`);
out.push(`// 再生成: node tools/gen_swift_data.mjs`);
out.push(``);
out.push(`import Foundation`);
out.push(``);
out.push(`struct Genre: Identifiable, Hashable {`);
out.push(`    let id: Int`);
out.push(`    let name: String`);
out.push(`    let detail: String`);
out.push(`    /// Asset カタログ上の画像名 (例: "8010")`);
out.push(`    var imageName: String { String(id) }`);
out.push(`}`);
out.push(``);
out.push(`struct Choice: Identifiable, Hashable {`);
out.push(`    let id: Int`);
out.push(`    let label: String`);
out.push(`    /// BikeFitData.genres と同じ並びの加点。要素数は genres.count と一致する。`);
out.push(`    let scores: [Int]`);
out.push(`}`);
out.push(``);
out.push(`struct Question: Identifiable, Hashable {`);
out.push(`    let id: Int`);
out.push(`    let section: String`);
out.push(`    let body: String`);
out.push(`    let choices: [Choice]`);
out.push(`}`);
out.push(``);
out.push(`enum BikeFitData {`);
out.push(``);
out.push(`    static let genres: [Genre] = [`);
for (const g of genres) {
  const d = escMultiline(descriptions.get(g.id));
  out.push(`        Genre(id: ${g.id}, name: "${esc(g.name)}", detail: """`);
  for (const line of d.split('\n')) out.push(`            ${line}`);
  out.push(`            """),`);
}
out.push(`    ]`);
out.push(``);
out.push(`    static let questions: [Question] = [`);
for (const q of questions.sort((a, b) => a.ono - b.ono)) {
  out.push(`        Question(id: ${q.id}, section: "${esc(q.section)}", body: "${esc(q.body)}", choices: [`);
  const qOptions = options.filter(o => o.questionId === q.id).sort((a, b) => a.sno - b.sno);
  for (const o of qOptions) {
    const row = scoreMatrix.get(o.id).join(', ');
    out.push(`            Choice(id: ${o.id}, label: "${esc(o.label)}", scores: [${row}]),`);
  }
  out.push(`        ]),`);
}
out.push(`    ]`);
out.push(``);
out.push(`    /// 選んだ選択肢からジャンル別合計点を出す。`);
out.push(`    /// Laravel 版 BfWeight::getDiagnostic と同じ計算。`);
out.push(`    static func score(for choices: [Choice]) -> [Int] {`);
out.push(`        var totals = [Int](repeating: 0, count: genres.count)`);
out.push(`        for choice in choices {`);
out.push(`            for (index, value) in choice.scores.enumerated() {`);
out.push(`                totals[index] += value`);
out.push(`            }`);
out.push(`        }`);
out.push(`        return totals`);
out.push(`    }`);
out.push(``);
out.push(`    /// 最高得点のジャンル。同点なら ID の小さい方を採る`);
out.push(`    /// (Laravel 版 BikefitService::getBestOne と同じ挙動)。`);
out.push(`    static func bestGenre(for choices: [Choice]) -> Genre {`);
out.push(`        let totals = score(for: choices)`);
out.push(`        var bestIndex = 0`);
out.push(`        for (index, value) in totals.enumerated() where value > totals[bestIndex] {`);
out.push(`            bestIndex = index`);
out.push(`        }`);
out.push(`        return genres[bestIndex]`);
out.push(`    }`);
out.push(`}`);
out.push(``);

mkdirSync(dirname(OUT_PATH), { recursive: true });
writeFileSync(OUT_PATH, out.join('\n'), 'utf8');

console.log(`#GEN04: ${OUT_PATH} を生成しました`);
console.log(`  質問 ${questions.length} / 選択肢 ${options.length} / ジャンル ${genres.length} / 重み ${weights.length}`);
