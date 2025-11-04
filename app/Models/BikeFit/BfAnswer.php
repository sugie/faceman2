<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BikeFit: 回答 (bf_answers)
 */
class BfAnswer extends Model
{
    protected $table = 'bf_answers';

    public $timestamps = false;

    protected $fillable = [
        'bf_user_id', 'bf_diagnosis_id', 'question_id', 'option_id', 'updated_at', 'created_at'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(BfUser::class, 'bf_user_id');
    }

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(BfDiagnosis::class, 'bf_diagnosis_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(BfQuestion::class, 'question_id');
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(BfOption::class, 'option_id');
    }
}
