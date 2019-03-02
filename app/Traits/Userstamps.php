<?php
namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

/**
| テーブルの「created_by」、「updated_by」カラムにログインユーザーIDを設定する
| 本トレイトは利用するモデルで以下のように宣言することで利用可能となる。
|   use App\Traits\Model\Userstamps;
|   
|   class XXX
|   {
|       use Userstamps;
|   }
*/
trait Userstamps
{
    /**
     * boot
     *
     */
    public static function bootUserstamps()
    {
        /**
         * 作成前
         */
        static::creating(function(Model $model) {
            if (empty($model->created_by)) {
                $model->created_by = Auth::id();
            }
        });

        /**
         * 更新前
         */
        static::updating(function(Model $model) {
            $model->updated_by = Auth::id();
        });

        /**
         * 保存時
         */
        static::saving(function(Model $model) {
            $model->updated_by = Auth::id();
        });
    }
}
