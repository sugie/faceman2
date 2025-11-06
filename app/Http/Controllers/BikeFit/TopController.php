<?php

namespace App\Http\Controllers\BikeFit;

use App\Http\Controllers\Controller;
use App\Models\BikeFit\BfDiagnosis;
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
    const BIKEFIT_DIAGNOSIS_ID_KEY = 'bikefit_diagnosis_id';

    /**
     * トップページ表示
     *
     * @return View ビュー bikefit.index を返却
     */
    public function index(): View
    {
        $visitorSession = session(self::VISITOR_SESSION_KEY);
        if (is_null($visitorSession)) {
            // 将来過去に診断した人を取り出す場合を想定するが、今回は全員新規訪問者として扱う
            // 新規訪問者の場合、ユニークIDをセッションに保存
            $uniqueId = uniqid('visitor_', true);
            session([self::VISITOR_SESSION_KEY => $uniqueId]);
            $bf_user = BfUser::create([
                'visitor_id' => $uniqueId,
                'updated_at' => now(), 'created_at' => now(),
            ]);

            // カラのbf_diagnosesを作成する
            $bf_diagnosis = BfDiagnosis::create(['bf_user_id' => $bf_user->id, 'updated_at' => now(), 'created_at' => now(),]);
            session([self::BIKEFIT_DIAGNOSIS_ID_KEY => $bf_diagnosis->id]);
        }

        session([AnswerController::PROGRESS_SESSION_KEY => 0]);
        // #BFT01: BikeFitトップを表示するだけ。将来的にA/Bテストや案内文の取得を追加する余地あり。
        return view('bikefit.index');
    }
}
