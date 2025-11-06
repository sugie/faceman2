<?php

namespace App\Http\Controllers\BikeFit;

use App\Http\Controllers\Controller;
use App\Models\BikeFit\BfDiagnosis;
use App\Models\BikeFit\BfUser;
use App\Models\BikeFit\BfWeight;
use App\Services\Bikefit\BikefitService;
use Illuminate\Contracts\View\View;

class ResultController extends Controller
{

    /**
     * 診断結果ページを表示
     */
    public function show(): View|\Illuminate\Http\RedirectResponse
    {
        $visitor = session(TopController::VISITOR_SESSION_KEY);
        if (!$visitor) {
            // bikefit.indexに遷移
            return redirect()->route('bikefit.index');
        }
        $bfUser = BfUser::where('visitor_id', $visitor)->first();
        $bf_diagnosis_id = session(TopController::BIKEFIT_DIAGNOSIS_ID_KEY);

        $genre_scores = BfWeight::getDiagnostic($bf_diagnosis_id, null);
        $betOneGunreId = BikefitService::getBestOne($genre_scores);

        logger()->info('#RC31: 診断結果画面表示', [
            'visitor' => $visitor,
            'bf_user_id' => $bfUser->id,
            'bf_diagnosis_id' => $bf_diagnosis_id,
            'best_genre_id' => $betOneGunreId,
            'genre_scores' => $genre_scores,
        ]);

        // 単純に選択肢ラベルとスコア（存在すれば）をビューに渡す
        return view('bikefit.result', [
            'genre_scores' => $genre_scores,
            'best_genre_id' => $betOneGunreId,
        ]);
    }
}

