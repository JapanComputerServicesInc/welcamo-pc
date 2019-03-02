<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdmissionRequest;
use Illuminate\Http\Request;

use App\Eloquents\Admission;

use Carbon\Carbon;
use Vinkla\Alert\Facades\Alert;

class AdmissionsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Purposes Controller
    |--------------------------------------------------------------------------
    |
    | 入館証 コントローラー
    | 入館証マスタ一覧、入館証追加、更新、削除機能を提供します。
    |
    | [制約]
    | 1) 認証済ユーザーのみ：configのrouteにてauthミドルウェア呼出しによる制御
    | 2) 管理ユーザーのみ　：configのrouteにてadmin認可のミドルウェア呼出しによる制御
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
     * 入館証一覧画面の表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        /* 入館証（新規）、入館証一覧 */
        $admission  = new Admission;
        $admissions = Admission::whereNull('deleted_at')->get();

        /* レスポンス */
        return view('admissions', compact('admission', 'admissions'));
    }

    /**
     * 入館証追加処理
     *
     * @param StoreAdmissionRequest $request
     * @return View
     */
    public function store(StoreAdmissionRequest $request)
    {
        $validated = $request->validated();
        $admission = self::set($validated, false);

        // 保存
        $admission->display_no = $admission->no;
        $admission->save();

        /* レスポンス */
        return redirect('admissions');
    }

    /**
     * 入館証更新処理
     *
     * @param StoreAdmissionRequest $request
     * @return View
     */
    public function update(StoreAdmissionRequest $request)
    {
        $validated = self::getLineArray($request->all());
        $admission = self::set($validated, true);

        if (!$admission) {
            /* 異常リクエスト例外 */
            abort(403, 'Unauthorized action.');
        }

        // 保存
        $admission->display_no = $admission->no;
        $admission->save();

        /* レスポンス */
        return redirect('admissions');
    }

    /**
     * 入館証削除処理
     *
     * @param Request $request
     * @return View
     */
    public function delete(Request $request)
    {
        $input     = self::getLineArray($request->all());
        $admission = self::set($input, true);

        if (!$admission) {
            /* 異常リクエスト例外 */
            abort(403, 'Unauthorized action.');
        }

        // 削除
        $admission->no         = null;
        $admission->deleted_at = Carbon::now();
        $admission->save();

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect('admissions');
    }

    /**
     * リソース設定処理
     *
     * @param array $values 設定値
     * @param bool $hasId true: ID有, false: ID無
     * @return Admission
     */
    private static function set($values, $hasId)
    {
        if ($hasId && !isset($values['id'])) {
            return null;
        }

        $admission = new Admission;
        if (isset($values['id'])) {
            $admission = Admission::find($values['id']);
            if (!$admission) {
                return null;
            }
        }
        $admission->no = $values['no'] ?? "";

        return $admission;
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
        $admission = [];

        if (!isset($index) || $index < 0) {
            return $admission;
        }

        $admission['id'] = $values['id'][$index];
        $admission['no'] = isset($values['e_no']) ? $values['e_no'][$index] : "";

        return $admission;
    }

}
