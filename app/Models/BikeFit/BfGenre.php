<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * BikeFit: ジャンル (bf_genres)
 */
class BfGenre extends Model
{
    protected $table = 'bf_genres';

    public $timestamps = false; // タイムスタンプなし

    protected $fillable = ['name','updated_at', 'created_at'];

    public function weights(): HasMany
    {
        return $this->hasMany(BfWeight::class, 'genre_id');
    }

    public function diagnosisScores(): HasMany
    {
        return $this->hasMany(BfDiagnosisScore::class, 'genre_id');
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(BfRecommendation::class, 'genre_id');
    }
}
