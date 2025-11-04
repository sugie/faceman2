<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * BikeFit: 診断 (bf_diagnoses)
 */
class BfDiagnosis extends Model
{
    protected $table = 'bf_diagnoses';

    public $timestamps = false; // created_atのみ。updated_atなし

    protected $fillable = [
        'bf_user_id', 'summary', 'updated_at', 'created_at'
    ];

    protected $casts = [
        'summary' => 'array',
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(BfUser::class, 'bf_user_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(BfAnswer::class, 'diagnosis_id');
    }

    public function diagnosisScores(): HasMany
    {
        return $this->hasMany(BfDiagnosisScore::class, 'diagnosis_id');
    }
}
