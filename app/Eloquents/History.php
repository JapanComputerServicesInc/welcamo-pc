<?php

namespace App\Eloquents;

use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

/**
| ���ٗ����̃��f��
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
    | ���ٗ����̃��f��
    |
    |
    */

    /**
     * created_by�Aupdated_by�p��Trait
     * 
     */
    use Userstamps;

    /**
     * ���f���Ɗ֘A���Ă���e�[�u��
     *
     * @var string
     */
    protected $table = 'histories';

    /**
     * ���f���Ɗ֘A���Ă���e�[�u���̎�L�[
     *
     * @var int
     */
    protected $primaryKey = 'id';

    /**
     * ���f���̃^�C���X�^���v���X�V���邩�̎w��
     *   - created_at, updated_at
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * ����������鑮��
     *
     * @var array
     */
    // protected $fillable = [];

    /**
     * ����������Ȃ�����
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * ��\���ɂ��鑮��
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * ���t����
     *
     * @var array
     */
    protected $dates = [
        'visit_dt',
        'last_dt',
        'approval_dt',
    ];

    /**
     * ���������p�N�G���X�R�[�v
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
