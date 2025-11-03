<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BikeFit: 重み (bf_weights)
 */
class BfWeight extends Model
{
    protected $table = 'bf_weights';

    public $timestamps = false;

    protected $fillable = [
        'question_id', 'option_id', 'genre_id', 'score',
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
}
