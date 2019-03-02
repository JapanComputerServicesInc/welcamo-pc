<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Eloquents\Name;
use App\Eloquents\User;

use Carbon\Carbon;
use Vinkla\Alert\Facades\Alert;

class UsersController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Users Controller
    |--------------------------------------------------------------------------
    |
    | ユーザー管理用コントローラー
    | ユーザー一覧、ユーザー追加、編集、削除機能を提供します。
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
     * ユーザー一覧画面の表示
     *
     * @param SearchUserRequest $request
     * @return View
     */
    public function index(SearchUserRequest $request)
    {
        $validated = $request->validated();
        $criteria  = $validated['criteria'] ?? "";

        /* ユーザー（新規）、ユーザー一覧*/
        $user  = new User;
        $users = User::fillByName($criteria)->get();

        /* コンボボックス */
        $roles       = Name::role()->get();
        $receptions  = Name::reception()->get();

        /* レスポンス */
        return view('users',
            compact('user', 'users', 'roles', 'receptions', 'criteria'));
    }

    /**
     * ユーザー追加処理
     *
     * @param StoreUserRequest $request
     * @return View
     */
    public function store(StoreUserRequest $request)
    {
        $validated = $request->validated();
        $user      = self::set($validated, false);

        // 保存
        $user->save();

        /* レスポンス */
        return redirect('users');
    }

    /**
     * ユーザー更新処理
     *
     * @param StoreUserRequest $request
     * @return View
     */
    public function update(StoreUserRequest $request)
    {
        $validated = self::getLineArray($request->all());
        $user      = self::set($validated, true);

        if (!$user) {
            /* 異常リクエスト例外 */
            abort(403, 'Unauthorized action.');
        }

        // 保存
        $user->save();

        /* レスポンス */
        return redirect('users');
    }

    /**
     * ユーザー削除処理
     *
     * @param Request $request
     * @return View
     */
    public function delete(Request $request)
    {
        $input = self::getLineArray($request->all());
        $user  = self::set($input, true);

        if (!$user) {
            /* 異常リクエスト例外 */
            abort(403, 'Unauthorized action.');
        }

        // 削除
        $user->email      = null;
        $user->deleted_at = Carbon::now();
        $user->save();

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect('users');
    }

    /**
     * リソース設定処理
     *
     * @param array $values 設定値
     * @param bool $hasId true: ID有, false: ID無
     * @return User
     */
    private static function set($values, $hasId)
    {
        if ($hasId && !isset($values['id'])) {
            return null;
        }

        $user = new User;
        if (isset($values['id'])) {
            $user = User::find($values['id']);
            if (!$user) {
                return null;
            }
        }
        $user->email      = $values['email'] ?? "";
        $user->user_name  = $values['user_name'] ?? "";
        $user->short_name = $values['short_name'] ?? "";
        $user->role       = $values['role'] ?? "";
        $user->reception  = $values['reception'] ?? "";
        if (!empty($values['password'])) {
            $user->password = Hash::make($values['password']);
        }

        return $user;
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
        $user = [];

        if (!isset($index) || $index < 0) {
            return $user;
        }

        $user['id']         = $values['id'][$index];
        $user['email']      = isset($values['e_email']) ? $values['e_email'][$index] : "";
        $user['user_name']  = isset($values['e_user_name']) ? $values['e_user_name'][$index] : "";
        $user['short_name'] = isset($values['e_short_name']) ? $values['e_short_name'][$index] : "";
        $user['role']       = isset($values['e_role']) ? $values['e_role'][$index] : "";
        $user['reception']  = isset($values['e_reception']) ? $values['e_reception'][$index] : "";
        $user['password']   = isset($values['e_password']) ? $values['e_password'][$index] : "";

        return $user;
    }

}
