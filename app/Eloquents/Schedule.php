<?php

namespace App\Eloquents;

use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
| 入館予定のモデル
|
|
*/
class Schedule extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Schedule Model
    |--------------------------------------------------------------------------
    |
    | 入館予定のモデル
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
    protected $table = 'schedules';

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
     * 日付属性
     *
     * @var array
     */
    protected $dates = [
        'schedule_date',
    ];

    /**
     * モデルの「初期起動」メソッド
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('sort', function (Builder $builder) {
            $builder->orderBy('schedule_date', 'asc');
        });
    }

}
