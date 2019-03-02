<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePurposeRequest;
use Illuminate\Http\Request;

use App\Eloquents\Purpose;

use Carbon\Carbon;
use Vinkla\Alert\Facades\Alert;

class PurposesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Purposes Controller
    |--------------------------------------------------------------------------
    |
    | 入館理由 コントローラー
    | 入館理由マスタ一覧、入館理由追加、更新、削除機能を提供します。
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
     * 入館理由一覧画面の表示
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request)
    {
        /* 入館理由（新規）、入館理由一覧 */
        $purpose  = new Purpose;
        $purposes = Purpose::whereNull('deleted_at')->get();

        /* レスポンス */
        return view('purposes', compact('purpose', 'purposes'));
    }

    /**
     * 入館理由追加処理
     *
     * @param StorePurposeRequest $request
     * @return View
     */
    public function store(StorePurposeRequest $request)
    {
        $validated = $request->validated();
        $purpose = self::set($validated, false);

        // 保存
        $purpose->save();

        /* レスポンス */
        return redirect('purposes');
    }

    /**
     * 入館理由更新処理
     *
     * @param StorePurposeRequest $request
     * @return View
     */
    public function update(StorePurposeRequest $request)
    {
        $validated = self::getLineArray($request->all());
        $purpose = self::set($validated, true);

        if (!$purpose) {
            /* 異常リクエスト例外 */
            abort(403, 'Unauthorized action.');
        }

        // 保存
        $purpose->save();

        /* レスポンス */
        return redirect('purposes');
    }

    /**
     * 入館理由削除処理
     *
     * @param Request $request
     * @return View
     */
    public function delete(Request $request)
    {
        $input   = self::getLineArray($request->all());
        $purpose = self::set($input, true);

        if (!$purpose) {
            /* 異常リクエスト例外 */
            abort(403, 'Unauthorized action.');
        }

        $purpose->deleted_at = Carbon::now();
        $purpose->sort_no    = -1;
        $purpose->save();

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect('purposes');
    }

    /**
     * リソース設定処理
     *
     * @param array $values 設定値
     * @param bool $hasId true: ID有, false: ID無
     * @return Purpose
     */
    private static function set($values, $hasId)
    {
        if ($hasId && !isset($values['id'])) {
            return null;
        }

        $purpose = new Purpose;
        if (isset($values['id'])) {
            $purpose = Purpose::find($values['id']);
            if (!$purpose) {
                return null;
            }
        }
        $purpose->purpose = $values['purpose'] ?? "";
        $purpose->sort_no = $values['sort_no'] ?? "";

        return $purpose;
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
        $purpose = [];

        if (!isset($index) || $index < 0) {
            return $purpose;
        }

        $purpose['id']      = $values['id'][$index];
        $purpose['purpose'] = isset($values['e_purpose']) ? $values['e_purpose'][$index] : "";
        $purpose['sort_no'] = isset($values['e_sort_no']) ? $values['e_sort_no'][$index] : "";

        return $purpose;
    }

}
