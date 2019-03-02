<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreUserRequest extends FormRequest
{
    /**
     * 項目名の接頭語、接尾語配列
     */
    static $modifiers = [
        'prefix'   => '',
        'suffix'   => '',
        'line'     => '',
        'id'       => '',
        'password' => '',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param Request $request
     * @return array
     */
    public function rules(Request $request)
    {
        self::makeModifiers($request);

        return [
            self::$modifiers['prefix'] . 'email'      . self::$modifiers['suffix'] => 'bail|required|email|max:255',
            self::$modifiers['prefix'] . 'user_name'  . self::$modifiers['suffix'] => 'bail|required|max:20',
            self::$modifiers['prefix'] . 'short_name' . self::$modifiers['suffix'] => 'bail|required|max:10',
            self::$modifiers['prefix'] . 'role'       . self::$modifiers['suffix'] => 'bail|required',
            self::$modifiers['prefix'] . 'reception'  . self::$modifiers['suffix'] => 'bail|required',
            self::$modifiers['prefix'] . 'password'   . self::$modifiers['suffix'] => 'bail' . self::$modifiers['password'],
        ];
    }

    /**
     * バリデーションエラーのカスタム属性の取得
     *
     * @return array
     */
    public function attributes()
    {
        return [
            self::$modifiers['prefix'] . 'email'      . self::$modifiers['suffix'] => self::$modifiers['line'] . __('app.email'),
            self::$modifiers['prefix'] . 'user_name'  . self::$modifiers['suffix'] => self::$modifiers['line'] . __('app.user_name'),
            self::$modifiers['prefix'] . 'short_name' . self::$modifiers['suffix'] => self::$modifiers['line'] . __('app.user_short_name'),
            self::$modifiers['prefix'] . 'role'       . self::$modifiers['suffix'] => self::$modifiers['line'] . __('app.role'),
            self::$modifiers['prefix'] . 'reception'  . self::$modifiers['suffix'] => self::$modifiers['line'] . __('app.reception'),
            self::$modifiers['prefix'] . 'password'   . self::$modifiers['suffix'] => self::$modifiers['line'] . __('app.password'),
        ];
    }

    /**
     * 項目名のPrefixとSuffixのセットを取得する
     *
     * @param Request $request
     * @return array
     */
    private static function makeModifiers($request)
    {
        $validatePassword = '|required|between:6,20';

        if ($request->input('edit_index') !== null) {
            $lineNo = ((int)$request->input('edit_index')) + 1;
            self::$modifiers['prefix']   = 'e_';
            self::$modifiers['suffix']   = '.' . $request->input('edit_index');
            self::$modifiers['line']     = '[' . $lineNo . __('app.line') . ']';
            self::$modifiers['id']       = ',' . $request->input('id')[$request->input('edit_index')];

            $password = $request->input('e_password')[$request->input('edit_index')];
            if (empty($password)) {
                $validatePassword = '';
            }
        }

        self::$modifiers['password'] = $validatePassword;
    }
}
