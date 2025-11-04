<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * BikeFit: 選択肢 (bf_options)
 */
class BfOption extends Model
{
    protected $table = 'bf_options';

    public $timestamps = false;

    protected $fillable = [
        'sno', 'question_id', 'label','updated_at', 'created_at'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(BfQuestion::class, 'question_id');
    }

    public function weights(): HasMany
    {
        return $this->hasMany(BfWeight::class, 'option_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(BfAnswer::class, 'option_id');
    }
}
