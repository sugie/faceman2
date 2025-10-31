<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BikeFit トップ</title>
    <style>
        /* 画面をシンプルに整える最小限のスタイル。Viteや外部アセットに依存しない */
        :root { --bg:#0e1013; --panel:#151922; --accent:#00d1b2; --text:#e9eef4; --muted:#9aa7b2; }
        * { box-sizing: border-box; }
        body { margin:0; font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Hiragino Kaku Gothic ProN", Meiryo, sans-serif; background: var(--bg); color: var(--text); }
        header { padding: 28px 20px; border-bottom: 1px solid #232937; background: #0f1319aa; backdrop-filter: blur(6px); position: sticky; top: 0; }
        .container { max-width: 960px; margin: 0 auto; padding: 24px 20px; }
        h1 { margin: 0 0 8px; font-size: 28px; letter-spacing: 0.02em; }
        p.lead { margin: 0; color: var(--muted); }
        .panel { margin-top: 24px; background: var(--panel); border: 1px solid #232937; border-radius: 12px; padding: 20px; }
        .actions { margin-top: 16px; display: flex; gap: 12px; flex-wrap: wrap; }
        a.button, button.button { appearance: none; border: 0; background: var(--accent); color: #052522; padding: 12px 18px; border-radius: 10px; font-weight: 600; cursor: pointer; text-decoration: none; }
        a.button.secondary { background: #232937; color: var(--text); border: 1px solid #2d3445; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-top: 20px; }
        .card { background: #11161f; border: 1px solid #232937; border-radius: 12px; padding: 16px; }
        .card h3 { margin: 0 0 6px; font-size: 18px; }
        .card p { margin: 0; color: var(--muted); font-size: 14px; line-height: 1.6; }
        footer { color: #718096; font-size: 12px; padding: 28px 20px; text-align: center; }
        code.badge { padding: 2px 6px; background: #232937; border: 1px solid #2d3445; border-radius: 6px; font-size: 12px; }
    </style>
</head>
<body>
<header>
    <div class="container">
        <h1>BikeFit</h1>
        <p class="lead">あなたに合う自転車スタイルを見つける診断サービス</p>
    </div>
</header>
<main class="container">
    <section class="panel">
        <!-- ここはBIkeFitのトップ説明。最初は最小構成。必要になったらSPAやLivewireを適用する方針。 -->
        <h2 style="margin:0 0 8px; font-size:22px;">ようこそ</h2>
        <p style="margin:0; color: var(--muted);">いくつかの質問に答えるだけで、あなたに最適なバイクタイプやセッティングの方向性を提案します。</p>
        <div class="actions">
            <!-- 診断開始。実装前はプレースホルダー。後で実際のルートに差し替えます。 -->
            <a href="#" class="button" onclick="alert('診断フローは未実装です。後続タスクで追加します。'); return false;">診断をはじめる</a>
            <a href="#about" class="button secondary">BikeFitとは</a>
        </div>
        <div class="grid" style="margin-top:24px;">
            <div class="card">
                <h3>かんたん</h3>
                <p>5〜10分で回答完了。直感的に選べる質問で構成されています。</p>
            </div>
            <div class="card">
                <h3>ロジック</h3>
                <p>質問と重み付けは ER/設計に基づきデータベース管理。拡張しやすい構造です。</p>
            </div>
            <div class="card">
                <h3>おすすめ</h3>
                <p>目的や体格に合わせて、バイクタイプや調整の方向性を提示します。</p>
            </div>
        </div>
    </section>

    <section id="about" class="panel">
        <h2 style="margin:0 0 8px; font-size:20px;">BikeFitとは</h2>
        <p class="lead" style="margin:0;">ライフスタイルや体格、目的に合わせて、最適なバイクタイプを見つけるための診断ツールです。</p>
        <div style="margin-top:12px; color: var(--muted); font-size:14px; line-height:1.8;">
            <!-- ストーリー性のある説明を短く。詳細は docs/ に配置予定。 -->
            近所の買い物からロングライドまで、あなたの「こうしたい」を起点に設計された質問票に答えることで、<br>
            クロスバイク・ロード・グラベルなどの選択や、ポジション調整の方向性をガイドします。
        </div>
        <div style="margin-top:12px;">
            <span class="muted">関連ドキュメント:</span>
            <span> <code class="badge">docs/bike_diagnosis_schema.sql</code> </span>
        </div>
    </section>
</main>
<footer>
    <div class="container">
        <small>&copy; {{ date('Y') }} BikeFit. All rights reserved.</small>
    </div>
</footer>
</body>
</html>
