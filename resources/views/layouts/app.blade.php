<!doctype html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Faceman2')</title>

    <style>
        /* ベース */
        html, body { height: 100%; margin: 0; font-family: -apple-system, BlinkMacSystemFont, "Helvetica Neue", "Segoe UI", Roboto, "Noto Sans JP", "Hiragino Kaku Gothic ProN", "Yu Gothic", "Meiryo", sans-serif; color: #222; }
        a { color: #0d6efd; text-decoration: none; }
        a:hover { text-decoration: underline; }

        /* ヘッダー */
        .site-header { background: #0d6efd; color: #fff; display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; }
        .brand a { color: #fff; font-weight: 700; font-size: 1.125rem; }
        .nav { display: flex; gap: 1rem; align-items: center; }
        .nav a { color: #fff; padding: 0.25rem 0.5rem; }

        /* コンテナ */
        .container { max-width: 1000px; margin: 1rem auto; padding: 0 1rem; box-sizing: border-box; }

        /* フッター */
        footer { padding: 1rem; background: #f8f9fa; text-align: center; color: #666; margin-top: 2rem; }

        /* モバイル用トグルボタン */
        .menu-toggle { display: none; background: none; border: none; color: #fff; font-size: 1.4rem; }

        /* 画面幅 600px 以下はモバイルレイアウト */
        @media (max-width: 600px) {
            .nav { display: none; position: absolute; top: 56px; left: 0; right: 0; background: #0b5ed7; flex-direction: column; padding: 0.5rem 1rem; }
            .nav.open { display: flex; }
            .menu-toggle { display: block; }
            .container { margin: 0.75rem auto; }
        }

        /* フォームの基本スタイル */
        form { max-width: 700px; }
        button { background: #0d6efd; color: #fff; border: none; padding: 0.6rem 1rem; border-radius: 4px; cursor: pointer; }
        button:hover { opacity: 0.95; }

    </style>

    @stack('styles')
</head>
<body>
    <header class="site-header">
        <div class="brand"><a href="{{ url('/') }}">Faceman2</a></div>

        <nav class="nav" aria-label="Global navigation">
            <a href="{{ route('bikefit.index') }}">BikeFit</a>
            <a href="{{ route('bikefit.answer') }}">診断を始める</a>
        </nav>

        <button class="menu-toggle" aria-label="メニュー" onclick="document.querySelector('.nav').classList.toggle('open')">☰</button>
    </header>

    <main class="container">
        @yield('content')
    </main>

    <footer>
        © {{ date('Y') }} Faceman2
    </footer>

    <script>
        // ユーザ補助: キーボードでトグルできるようにする
        (function(){
            var btn = document.querySelector('.menu-toggle');
            if(!btn) return;
            btn.addEventListener('keydown', function(e){
                if(e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    document.querySelector('.nav').classList.toggle('open');
                }
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>

