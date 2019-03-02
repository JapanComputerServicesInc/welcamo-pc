<?php

namespace App\Eloquents;

use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Visitor Model
    |--------------------------------------------------------------------------
    |
    | 入館者のモデル
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
    protected $table = 'visitors';

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
        'exit_dt',
    ];
}
