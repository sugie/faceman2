<?php

namespace App\Http\Controllers\BikeFit;

use App\Http\Controllers\Controller;
use App\Models\BikeFit\BfUser;
use App\Models\User;
use Illuminate\Contracts\View\View;

/**
 * BikeFit トップページ用コントローラー
 *
 * 単一責任: BikeFit のトップビューを返すことのみを担う。
 */
class TopController extends Controller
{
    const VISITOR_SESSION_KEY = 'bikefit_visitor_id';
    /**
     * トップページ表示
     *
     * @return View ビュー bikefit.index を返却
     */
    public function index(): View
    {
        $visitorSession = session(self::VISITOR_SESSION_KEY);
        if (is_null($visitorSession)) {
            // 新規訪問者の場合、ユニークIDをセッションに保存
            $uniqueId = uniqid('visitor_', true);
            session([self::VISITOR_SESSION_KEY => $uniqueId]);
            BfUser::create([
                'visitor_id' => $uniqueId
            ]);
        }

        // #BFT01: BikeFitトップを表示するだけ。将来的にA/Bテストや案内文の取得を追加する余地あり。
        return view('bikefit.index');
    }
}
