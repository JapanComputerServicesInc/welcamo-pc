<?php

namespace App\Eloquents;

use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Model;

class Name extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Name Model
    |--------------------------------------------------------------------------
    |
    | 本システムの名称と値の組み合わせを管理するモデル
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
    protected $table = 'names';

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


    /*
    |--------------------------------------------------------------------------
    | Local Scope
    |--------------------------------------------------------------------------
    */

    /**
     * 取得対象を役割（ROLE）だけに限定するクエリスコープ
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRole($query)
    {
        return $query->where([
            'key_cd'    => 'SYSTEM',
            'nm_key_cd' => 'ROLE'
        ])->orderBy('sort_no', 'asc');
    }

    /**
     * 取得対象を受付（RECEPTION）だけに限定するクエリスコープ
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReception($query)
    {
        return $query->where([
            'key_cd'    => 'SYSTEM',
            'nm_key_cd' => 'RECEPTION'
        ])->orderBy('sort_no', 'asc');
    }

    /**
     * 取得対象を入管エリア（ENTRY_AREA）だけに限定するクエリスコープ
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEntryArea($query)
    {
        return $query->where([
            'key_cd'    => 'SYSTEM',
            'nm_key_cd' => 'ENTRY_AREA'
        ])->orderBy('sort_no', 'asc');
    }

}
