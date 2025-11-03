<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * BikeFit: プロフィール (bf_profiles)
 */
class BfProfile extends Model
{
    protected $table = 'bf_profiles';

    public $timestamps = true; // created_at, updated_at あり

    protected $fillable = [
        'bf_user_id', 'height_cm', 'weight_kg', 'inseam_cm', 'experience_years', 'region', 'license', 'preferences',
    ];

    protected $casts = [
        'preferences' => 'array',
        'experience_years' => 'float',
    ];

    /**
     * ユーザー
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(BfUser::class, 'bf_user_id');
    }
}
