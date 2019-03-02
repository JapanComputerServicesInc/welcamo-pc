<?php

namespace App\Eloquents;

use App\Traits\Userstamps;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /*
    |--------------------------------------------------------------------------
    | User Model
    |--------------------------------------------------------------------------
    |
    | 本システムの認証ユーザーのモデル
    |
    |
    */

    use Notifiable;

    /**
     * created_by、updated_by用のTrait
     * 
     */
    use Userstamps;

    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * モデルと関連しているテーブルの主キー
     *
     * @var int
     */
    protected $primaryKey = 'id';

    /**
     * モデルのタイムスタンプを更新するかの指示
     *   - created_at, updated_at
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 複数代入する属性
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'user_name',
        'password',
        'role',
        'short_name',
        'reception'
    ];

    /**
     * 複数代入しない属性
     *
     * @var array
     */
    // protected $guarded = [];

    /**
     * 非表示にする属性
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /*
    |--------------------------------------------------------------------------
    | Local Scope
    |--------------------------------------------------------------------------
    */

    /**
     * ユーザー名による絞り込みを行うクエリスコープ
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $criteria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFillByName($query, $criteria)
    {
        $name = isset($criteria) ? '%' . $criteria . '%' : '%';

        return $query
            ->where('user_name', 'LIKE', $name)
            ->whereNull('deleted_at')
            ->orderBy('email', 'asc');
    }

    /**
     * 受付可能な担当者を取得するクエリスコープ
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReceptioner($query)
    {
        return $query
            ->where('reception', config('welcamo.reception_enable'))
            ->whereNull('deleted_at')
            ->orderBy('email', 'asc');
    }

}
