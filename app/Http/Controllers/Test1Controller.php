<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * /test1 のテストページ用のシンプルなコントローラー。
 * 役割はビューを返すのみ。SRPに従いロジックは持たない。
 */
class Test1Controller extends Controller
{
    /**
     * テストページを表示する。
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // #TDS01: return a simple test page view (Bootstrap5 via CDN)
        return view('test1');
    }
}
