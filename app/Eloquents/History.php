<?php

namespace App\Eloquents;

use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

/**
| 入館履歴のモデル
|
|
*/
class History extends Model
{
    /*
    |--------------------------------------------------------------------------
    | History Model
    |--------------------------------------------------------------------------
    |
    | 入館履歴のモデル
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
    protected $table = 'histories';

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
        'visit_dt',
        'last_dt',
        'approval_dt',
    ];

    /**
     * 履歴検索用クエリスコープ
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $year
     * @param string $month
     * @param string $criteria
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFillByCriteria($query, $year, $month, $criteria)
    {
        $fromDate = Carbon::create($year, $month, 1, 0, 0, 0);
        $toDate   = Carbon::create($year, $month, 1, 0, 0, 0)->addMonth();
        $name     = isset($criteria) ? '%' . $criteria . '%' : '%';

        return $query
            ->where('visit_dt'    , '>='  , $fromDate->format('Y-m-d H:i:s'))
            ->where('visit_dt'    , '<'   , $toDate->format('Y-m-d H:i:s'))
            ->where(function($query) use ($name) {
                return $query
                    ->where('company_name', 'LIKE', $name)
                    ->orwhere('visitor_name', 'LIKE', $name);
            })
        ;
    }

}
