<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 use Illuminate\Support\Collection;

/**
 * BikeFit: 重み (bf_weights)
 */
class BfWeight extends Model
{
    protected $table = 'bf_weights';

    public $timestamps = false;

    protected $fillable = [
        'question_id', 'option_id', 'genre_id', 'score', 'updated_at', 'created_at'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(BfQuestion::class, 'question_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(BfOption::class, 'option_id');
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(BfGenre::class, 'genre_id');
    }

    // Static property used by tests to inject weight data without hitting the DB.
    protected static array $testWeights = [];

    // Allow tests to set a map of weights keyed by "{question_id}:{option_id}".
    public static function setTestWeights(array $map): void
    {
        self::$testWeights = $map;
    }

    public static function resetTestWeights(): void
    {
        self::$testWeights = [];
    }

    /**
     * Calculate diagnostic genre scores for a diagnosis.
     *
     * - By default it loads answers from DB via BfAnswer::where(...)->get().
     * - For tests you can pass an array/Collection of answers as second arg
     *   and provide test weights via setTestWeights().
     *
     * @param int $bf_diagnosis_id
     * @param array|Collection|null $answers Optional: array/Collection of answer-like objects ({question_id, option_id}) for tests
     * @return array genre_id => total_score
     */
    public static function getDiagnostic(int $bf_diagnosis_id, $answers = null)
    {
        // If answers is not provided, fetch from DB as before.
        if ($answers === null) {
            $answer_list = BfAnswer::where('bf_diagnosis_id', $bf_diagnosis_id)->get();
        } elseif ($answers instanceof Collection) {
            $answer_list = $answers;
        } else {
            // Normalize array to a Collection of stdClass (or objects provided)
            $answer_list = collect($answers);
        }

        $genre_scores = [];
        foreach ($answer_list as $answer) {
            // If test weights are set, use them instead of querying DB.
            if (!empty(self::$testWeights)) {
                $key = ($answer->question_id ?? $answer['question_id'] ?? null) . ':' . ($answer->option_id ?? $answer['option_id'] ?? null);
                $weight_list = self::$testWeights[$key] ?? [];
            } else {
                $weight_list = self::where('question_id', $answer->question_id)
                    ->where('option_id', $answer->option_id)
                    ->get();
            }

            foreach ($weight_list as $weight) {
                // Support both array weight fixtures and Model instances.
                if (is_array($weight)) {
                    $genreId = $weight['genre_id'];
                    $score = $weight['score'];
                } else {
                    $genreId = $weight->genre_id;
                    $score = $weight->score;
                }

                if (!isset($genre_scores[$genreId])) {
                    $genre_scores[$genreId] = 0;
                }
                $genre_scores[$genreId] += $score;
            }
        }
        return $genre_scores;
    }
}
