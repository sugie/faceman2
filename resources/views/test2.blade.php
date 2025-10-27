<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test2 Canvas Page</title>
    <!-- Bootstrap 5 via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* レイアウトの簡易調整 */
        .canvas-wrap { display: flex; align-items: center; justify-content: center; }
        canvas { border: 1px solid #ddd; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Faceman2</a>
    </div>
</nav>
<main class="container py-4">
    <h1 class="display-6">/test2 キャンバス描画テスト</h1>
    <p class="lead">HTML5 の 256 x 256 ピクセルの CANVAS に真円を描画します。</p>

    <div class="canvas-wrap my-3">
        <!-- 要件: 256 x 256 pixel の CANVAS -->
        <canvas id="circleCanvas" width="256" height="256" aria-label="circle canvas"></canvas>
    </div>
</main>

<script>
    // 真円を描画する
    (function drawCircle() {
        const canvas = document.getElementById('circleCanvas');
        const ctx = canvas.getContext('2d');

        // キャンバスの中心座標
        const cx = canvas.width / 2; // 128
        const cy = canvas.height / 2; // 128
        // 半径: 端との余白を確保
        const RADIUS = Math.min(cx, cy) - 8; // 120px 程度

        // 背景を白で塗る（任意）
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        // 円のスタイル
        ctx.strokeStyle = '#0d6efd'; // Bootstrap primary 相当
        ctx.lineWidth = 4;

        // 真円を描画
        ctx.beginPath();
        ctx.arc(cx, cy, RADIUS, 0, Math.PI * 2, false);
        ctx.closePath();
        ctx.stroke();

        // 参考: 中心点の可視化（デバッグ用）。必要なければコメントアウト
        // ctx.fillStyle = '#dc3545';
        // ctx.beginPath();
        // ctx.arc(cx, cy, 2, 0, Math.PI * 2);
        // ctx.fill();
    })();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
