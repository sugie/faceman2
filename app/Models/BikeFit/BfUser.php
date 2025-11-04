<?php

namespace App\Models\BikeFit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * BikeFit: ユーザー (bf_users)
 */
class BfUser extends Model
{
    // テーブル名
    protected $table = 'bf_users';

    /**
     * 更新日時・作成日時カラムは存在する
     * Laravelのデフォルトを利用
     * @var bool
     */
    public $timestamps = true;

    /**
     * 変更可能属性
     * 注意: パスワードのハッシュ化はアプリケーションサービス層で行うこと
     * @var array<int,string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'visitor_id','updated_at', 'created_at'
    ];

    /**
     * プロフィール一覧
     */
    public function profiles(): HasMany
    {
        return $this->hasMany(BfProfile::class, 'bf_user_id');
    }

    /**
     * 診断履歴
     */
    public function diagnoses(): HasMany
    {
        return $this->hasMany(BfDiagnosis::class, 'bf_user_id');
    }
}
