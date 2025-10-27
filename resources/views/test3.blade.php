<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test3 Face Canvas</title>
    <!-- Bootstrap 5 via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* レイアウトの簡易調整 */
        .canvas-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        canvas {
            border: 1px solid #ddd;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Faceman2</a>
    </div>
</nav>
<main class="container py-4">
    <h1 class="display-6">/test3 顔の表情（happy怒哀楽）</h1>
    <p class="lead">256x256 の CANVAS に、真円の顔と「右目・左目・口」の3部品のみで表情を描画します。URL
        パラメータで表情を変更できます。</p>

    <div class="alert alert-secondary">
        例: ?mood=happy / ?mood=怒 / ?eye=哀&mouth=楽 など。<br>
        サポート: happy(happy/ki), 怒(angry/do), 哀(sad/ai), 楽(joy/raku), 中立(neutral)。
    </div>

    <div class="canvas-wrap my-3">
        <!-- 要件: 256 x 256 pixel の CANVAS -->
        <canvas id="faceCanvas" width="256" height="256" aria-label="face canvas"></canvas>
    </div>
</main>

<script>
    // #FCE01: /test3 顔の表情描画
    // 仕様: 真円(顔) + 右目・左目・口の3部品のみ。URLパラメータでhappy怒哀楽を表現する。
    (function () {
        const canvas = document.getElementById('faceCanvas');
        const ctx = canvas.getContext('2d');

        // マジックナンバー禁止: 定数としてまとめる
        const CANVAS_SIZE = 256;
        const CX = CANVAS_SIZE / 2; // 128
        const CY = CANVAS_SIZE / 2; // 128
        const FACE_RADIUS = 118; // 顔の真円半径（外枠との余白を確保）
        const STROKE_COLOR = '#212529'; // 顔の円
        const FEATURE_COLOR = '#212529'; // 目・口の色（濃いグレー）
        const LINE_WIDTH_FACE = 4;
        const LINE_WIDTH_FEATURE = 6;

        // 目の位置・サイズ
        const EYE_OFFSET_X = 48; // 中心から左右オフセット
        const EYE_Y = 100;       // 目のY位置
        const EYE_RADIUS = 12;   // 目の半径（円や短い弧の基準）
        const EYE_TILT = 6;      // 怒り目のつり上げ量(px) — 外側が上がる程度

        // 口の位置・サイズ
        const MOUTH_Y = 160;
        const MOUTH_RADIUS = 60; // 口の弧半径

        // 背景（任意）
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, CANVAS_SIZE, CANVAS_SIZE);

        // 顔の外周（真円）
        ctx.strokeStyle = STROKE_COLOR;
        ctx.lineWidth = LINE_WIDTH_FACE;
        ctx.beginPath();
        ctx.arc(CX, CY, FACE_RADIUS, 0, Math.PI * 2, false);
        ctx.closePath();
        ctx.stroke();

        // URL パラメータから表情指定を受け取る
        const params = new URLSearchParams(window.location.search);
        // mood 全体 or 個別 eye/mouth。優先順位: eye/mouth > mood > neutral
        // const moodParam = normalizeEmotion(params.get('mood'));
        // const moodParam = "happy"
        // const moodParam = "sad"
        const moodParam = "angry"
        // const moodParam = "neutral"
        const eyeParam = moodParam;
        const mouthParam = moodParam;
        const eyeEmotion = moodParam;
        const mouthEmotion = mouthParam;

        // 目と口を描画（3部品）
        ctx.strokeStyle = FEATURE_COLOR;
        ctx.lineWidth = LINE_WIDTH_FEATURE;
        drawEye(ctx, CX - EYE_OFFSET_X, EYE_Y, eyeEmotion, 'left');
        drawEye(ctx, CX + EYE_OFFSET_X, EYE_Y, eyeEmotion, 'right');
        drawMouth(ctx, CX, MOUTH_Y, mouthEmotion);

        // ----- 関数群 -----
        // happy怒哀楽/別名を正規化
        function normalizeEmotion(v) {
            if (!v) return '';
            const s = String(v).trim().toLowerCase();
            // 日本語と英語・ローマ字の対応
            const map = {
                'happy': 'happy',
                'うれしい': 'happy',
                '嬉しい': 'happy',
                'happy': 'happy',
                'ki': 'happy',
                'joy': 'happy',
                '楽': 'happy',
                'raku': 'happy',
                'angry': 'angry',
                'おこり': 'angry',
                '怒り': 'angry',
                'angry': 'angry',
                'do': 'angry',
                'sad': 'sad',
                'かなしい': 'sad',
                '悲しい': 'sad',
                'sad': 'sad',
                'ai': 'sad',
                'neutral': 'neutral',
                '普通': 'neutral',
                'ふつう': 'neutral',
                'normal': 'neutral'
            };
            return map[s] || s;
        }

        // 目を描画
        // 種類:
        // - happy: ゆるい下向き弧  (∪ の一部) 目が細く笑っている
        // - angry: 水平ライン      (ーー) 目を細めた怒り
        // - sad:   上向き弧        (∩ の一部) しょんぼり
        // - neutral: 小さな円点    (・)
        function drawEye(ctx, x, y, emotion, side) {
            ctx.beginPath();
            switch (emotion) {
                case 'happy': // 楽/happy
                    // 下向きの浅い弧
                    arcStroke(ctx, x, y, EYE_RADIUS, Math.PI * 0.15, Math.PI * 0.85);
                    break;
                case 'angry': // 怒
                    // つり上がった斜めライン（外側が上がる）
                    const t = EYE_TILT;
                    if (side === 'left') {
                        // 左目: 外側(左端)を上げ、内側(右端)を下げる
                        lineStroke(ctx, x - EYE_RADIUS, y - t, x + EYE_RADIUS, y + t);
                    } else {
                        // 右目: 内側(左端)を下げ、外側(右端)を上げる
                        lineStroke(ctx, x - EYE_RADIUS, y + t, x + EYE_RADIUS, y - t);
                    }
                    break;
                case 'sad': // 哀
                    // 上向きの弧
                    arcStroke(ctx, x, y, EYE_RADIUS, Math.PI * 1.15, Math.PI * 1.85);
                    break;
                default: // neutral
                    // 小さな点
                    ctx.arc(x, y, 3, 0, Math.PI * 2);
                    break;
            }
            ctx.stroke();
        }

        // 口を描画
        // - happy: 下向きの大きめ弧  にっこり
        // - angry: 水平ライン        への字ではなく引き結んだ口
        // - sad:   上向きの弧        への字
        // - neutral: 短い水平ライン
        function drawMouth(ctx, cx, y, emotion) {
            ctx.beginPath();
            switch (emotion) {
                case 'happy':
                    arcStroke(ctx, cx, y, MOUTH_RADIUS, Math.PI * 0.15, Math.PI * 0.85);
                    break;
                case 'angry':
                    lineStroke(ctx, cx - MOUTH_RADIUS * 0.7, y, cx + MOUTH_RADIUS * 0.7, y);
                    break;
                case 'sad':
                    arcStroke(ctx, cx, y, MOUTH_RADIUS, Math.PI * 1.15, Math.PI * 1.85);
                    break;
                default: // neutral
                    lineStroke(ctx, cx - MOUTH_RADIUS * 0.4, y, cx + MOUTH_RADIUS * 0.4, y);
                    break;
            }
            ctx.stroke();
        }

        // 補助: 弧を描画（中心(cx,cy), 半径r, 開始角, 終了角）
        function arcStroke(ctx, cx, cy, r, start, end) {
            ctx.arc(cx, cy, r, start, end, false);
        }

        // 補助: 直線
        function lineStroke(ctx, x1, y1, x2, y2) {
            ctx.moveTo(x1, y1);
            ctx.lineTo(x2, y2);
        }
    })();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
