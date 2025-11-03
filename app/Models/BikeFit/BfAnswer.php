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
        'diagnosis_id', 'question_id', 'option_id',
    ];

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(BfDiagnosis::class, 'diagnosis_id');
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
