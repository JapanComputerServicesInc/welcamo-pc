<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use Illuminate\Http\Request;

use App\Eloquents\Schedule;

use Carbon\Carbon;
use Vinkla\Alert\Facades\Alert;

class SchedulesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Schedules Controller
    |--------------------------------------------------------------------------
    |
    | 入館予定 コントローラー
    | 入館予定一覧、入館予定追加、削除機能を提供します。
    |
    | [制約]
    | 1) 認証済ユーザーのみ：configのrouteにてauthミドルウェア呼出しによる制御
    |
    */

    /**
     * コンストラクタ
     *
     */
    public function __construct()
    {
    }

    /**
     * 入館予定一覧画面の表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        /* 入館予定（新規）、入館予定一覧 */
        $schedule  = new Schedule;
        $schedules = Schedule::all();

        /* レスポンス */
        return view('schedules', compact('schedule', 'schedules'));
    }

    /**
     * 入館予定追加処理
     *
     * @param StoreScheduleRequest $request
     * @return View
     */
    public function store(StoreScheduleRequest $request)
    {
        $validated = $request->validated();
        $schedule  = self::set($validated, false);

        // 保存
        $schedule->save();

        /* レスポンス */
        return redirect('schedules');
    }

    /**
     * 入館予定削除処理
     *
     * @param Request $request
     * @return View
     */
    public function delete(Request $request)
    {
        $input    = self::getLineArray($request->all());
        $schedule = self::set($input, true);

        if (!$schedule) {
            /* 異常リクエスト例外 */
            abort(403, 'Unauthorized action.');
        }

        // 削除
        $schedule->delete();

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect('schedules');
    }

    /**
     * リソース設定処理
     *
     * @param array $values 設定値
     * @param bool $hasId true: ID有, false: ID無
     * @return Schedule
     */
    private static function set($values, $hasId)
    {
        if ($hasId && !isset($values['id'])) {
            return null;
        }

        $schedule = new Schedule;
        if (isset($values['id'])) {
            $schedule = Schedule::find($values['id']);
            if (!$schedule) {
                return null;
            }
        }

        $schedule->schedule_date = isset($values['schedule_date']) ? self::parseDate($values['schedule_date']) : "";
        $schedule->company_name  = $values['company_name'] ?? "";
        $schedule->visitor_name  = $values['visitor_name'] ?? "";

        return $schedule;
    }

    /**
     * 更新、削除時の配列データより処理対象データの配列を取得する
     *
     * @param array $values 設定値
     * @return array
     */
    private static function getLineArray($values)
    {
        $index   = $values['edit_index'] ?? null;
        $schedule = [];

        if (!isset($index) || $index < 0) {
            return $schedule;
        }

        $schedule['id'] = $values['id'][$index];

        return $schedule;
    }

    /**
     * 日付文字列を作成する
     *
     * @param string $str
     * @return string
     */
    private static function parseDate($str)
    {
        if (!isset($str) || empty($str)) {
            return $str;
        }

        $separate = (strpos($str, '/') !== false) ? '/' : '';
        $separate = (strpos($str, '-') !== false) ? '-' : $separate;

        if ($separate != '' && strlen($str) > 10) {
            return $str;
        }

        $tmp = explode($separate, $str);
        $day = Carbon::create($tmp[0], $tmp[1], $tmp[2], 0, 0, 0);

        return $day->format('Y-m-d H:i:s');
    }

}
