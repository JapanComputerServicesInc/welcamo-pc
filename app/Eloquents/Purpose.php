<?php

namespace App\Eloquents;

use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Purpose extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Purpose Model
    |--------------------------------------------------------------------------
    |
    | 入館理由のモデル
    |
    |
    */

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
    protected $table = 'purposes';

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
    // protected $fillable = [];

    /**
     * 複数代入しない属性
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * 非表示にする属性
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * モデルの「初期起動」メソッド
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderBy('sort_no', 'asc');
        });
    }

}
