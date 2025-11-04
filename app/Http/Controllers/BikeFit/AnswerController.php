<?php

namespace App\Http\Controllers\BikeFit;

use App\Http\Controllers\Controller;
use App\Models\BikeFit\BfUser;
use App\Models\BikeFit\BfQuestion;
use App\Models\BikeFit\BfDiagnosis;
use App\Models\BikeFit\BfAnswer;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class AnswerController extends Controller
{
    const PROGRESS_SESSION_KEY = 'bikefit_progress';

    /**
     * 質問フォームを表示（簡易版: 全質問の最初の1問のみ表示する例）
     */
    public function index(): View|\Illuminate\Http\RedirectResponse
    {
        $visitor = session(TopController::VISITOR_SESSION_KEY);
        if (!$visitor) {
            // bikefit.indexに遷移
            return redirect()->route('bikefit.index');
        }
        $bfUser = BfUser::where('visitor_id', $visitor)->first();

        $bf_progress = session(self::PROGRESS_SESSION_KEY, 1);
        session([self::PROGRESS_SESSION_KEY => $bf_progress]);

        $bf_diagnosis_id = session(TopController::BIKEFIT_DIAGNOSIS_ID_KEY);

        $question = BfQuestion::where('id', '=', $bf_progress)->with('options')->first();

        return view('bikefit.answer', [
            'question' => $question,
            'bfUser' => $bfUser,
            'bf_progress' => $bf_progress,
        ]);
    }

    /**
     * 回答を受け取り、診断レコードと回答を保存し、リダイレクト
     */
    public function store(Request $request)
    {
        $bf_progress = session(self::PROGRESS_SESSION_KEY);
        $bf_progress++;
        session([self::PROGRESS_SESSION_KEY => $bf_progress]);
        $bf_diagnosis_id = session(TopController::BIKEFIT_DIAGNOSIS_ID_KEY);

        $visitor = session(TopController::VISITOR_SESSION_KEY);
        if (!$visitor) {
            // bikefit.indexに遷移
            return redirect()->route('bikefit.index');
        }
        $bfUser = BfUser::where('visitor_id', $visitor)->first();

        $request->validate([
            'question_id' => 'required|integer',
            'option_id' => 'required|integer',
        ]);

        // 回答を保存
        BfAnswer::create([
            'user_id' => $bfUser ? $bfUser->id : null,
            'bf_diagnosis_id' => $bf_diagnosis_id,
            'question_id' => $request->input('question_id'),
            'option_id' => $request->input('option_id'),
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        $max_id = BfQuestion::max('id');
        //if ($bf_progress == $max_id) {
        if ($bf_progress == 2) {
            // 最後の質問に回答した場合、診断結果ページへリダイレクト
            // 診断レコードを作成
            return redirect()->route('bikefit.result', []);
        }

        return redirect()->route('bikefit.answer', []);
    }
}

