<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BikeFit: 診断スコア (bf_diagnosis_scores)
 */
class BfDiagnosisScore extends Model
{
    protected $table = 'bf_diagnosis_scores';

    public $timestamps = false;

    protected $fillable = [
        'diagnosis_id', 'genre_id', 'score', 'rank',
    ];

    public function diagnosis(): BelongsTo
    {
        return $this->belongsTo(BfDiagnosis::class, 'diagnosis_id');
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(BfGenre::class, 'genre_id');
    }
}
