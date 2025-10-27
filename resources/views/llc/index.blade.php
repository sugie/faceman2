<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>生涯学習センター検索 - 画面1</title>
    @livewireStyles
    <style>
        body { font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans JP", "Hiragino Kaku Gothic ProN", Meiryo, sans-serif; margin: 20px; }
        .container { max-width: 720px; margin: 0 auto; }
        .card { border: 1px solid #ddd; padding: 16px; border-radius: 8px; }
        .btn { padding: 8px 12px; display: inline-block; background: #2563eb; color: #fff; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #1e40af; }
        input { padding: 8px; }
    </style>
</head>
<body>
<div class="container">
    <h1>生涯学習センター検索</h1>
    <p>画面1: 郵便番号を入力してください。</p>

    <div class="card">
        <form action="{{ route('llc.search') }}" method="get">
            <label for="zip" style="display:block; font-weight:bold;">郵便番号（ハイフンなし）</label>
            <input id="zip" name="zip" type="text" placeholder="例: 1600022" maxlength="8" pattern="[0-9-]+" required>
            <button type="submit" class="btn" style="margin-left: 8px;">検索</button>
        </form>
    </div>

    <p style="margin-top:24px; font-size: 12px; color:#555;">入力した郵便番号の前方一致で候補を検索します。</p>
</div>

@livewireScripts
</body>
</html>
