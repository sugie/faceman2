<?php

namespace App\Http\Controllers\BikeFit;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

/**
 * BikeFit トップページ用コントローラー
 *
 * 単一責任: BikeFit のトップビューを返すことのみを担う。
 */
class TopController extends Controller
{
    /**
     * トップページ表示
     *
     * @return View ビュー bikefit.index を返却
     */
    public function index(): View
    {
        // #BFT01: BikeFitトップを表示するだけ。将来的にA/Bテストや案内文の取得を追加する余地あり。
        return view('bikefit.index');
    }
}
