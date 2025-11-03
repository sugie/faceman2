<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * BikeFit: 質問 (bf_questions)
 */
class BfQuestion extends Model
{
    protected $table = 'bf_questions';

    public $timestamps = false; // タイムスタンプなし

    protected $fillable = [
        'section', 'body', 'answer_type',
    ];

    public function options(): HasMany
    {
        return $this->hasMany(BfOption::class, 'question_id');
    }

    public function weights(): HasMany
    {
        return $this->hasMany(BfWeight::class, 'question_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(BfAnswer::class, 'question_id');
    }
}
