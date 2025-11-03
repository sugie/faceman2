<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BikeFit: 推奨リソース (bf_recommendations)
 */
class BfRecommendation extends Model
{
    protected $table = 'bf_recommendations';

    public $timestamps = false;

    protected $fillable = [
        'genre_id', 'type', 'title', 'url', 'region', 'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function genre(): BelongsTo
    {
        return $this->belongsTo(BfGenre::class, 'genre_id');
    }
}
