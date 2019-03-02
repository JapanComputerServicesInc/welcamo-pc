<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Vinkla\Alert\Facades\Alert;

use App\Eloquents\User;

class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Controller
    |--------------------------------------------------------------------------
    |
    | パスワード変更用コントローラー
    | ログインユーザーのパスワード変更機能を提供します。
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
     * パスワード変更画面の表示
     *
     * @return View
     */
    public function index()
    {
        /* レスポンス */
        return view('change_password');
    }

    /**
     * パスワード更新処理
     *
     * @param PasswordRequest $request
     * @return View
     */
    public function update(PasswordRequest $request)
    {
        $validated = $request->validated();
        $user = User::find(Auth::id());
        $user->password = Hash::make($validated['password']);
        $user->save();

        /* レスポンス */
        Alert::success(__('app.message_operation_success'));
        return redirect('change_password');
    }

}
