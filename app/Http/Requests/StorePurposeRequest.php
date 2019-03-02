<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StorePurposeRequest extends FormRequest
{
    /**
     * 項目名の接頭語、接尾語配列
     */
    static $modifiers = [
        'prefix' => '',
        'suffix' => '',
        'line'   => '',
        'id'     => '',
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
            self::$modifiers['prefix'] . 'purpose' . self::$modifiers['suffix'] => 'bail|required|max:40',
            self::$modifiers['prefix'] . 'sort_no' . self::$modifiers['suffix'] => 'bail|required|unique:purposes,sort_no'. self::$modifiers['id'] .'|integer|between:1,9999',
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
            self::$modifiers['prefix'] . 'purpose' . self::$modifiers['suffix'] => self::$modifiers['line'] . __('app.purpose'),
            self::$modifiers['prefix'] . 'sort_no' . self::$modifiers['suffix'] => self::$modifiers['line'] . __('app.sort_no'),
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
        if ($request->input('edit_index') !== null) {
            $lineNo = ((int)$request->input('edit_index')) + 1;
            self::$modifiers['prefix']  = 'e_';
            self::$modifiers['suffix']  = '.' . $request->input('edit_index');
            self::$modifiers['line']    = '[' . $lineNo . __('app.line') . ']';
            self::$modifiers['id']      = ',' . $request->input('id')[$request->input('edit_index')];
        }
    }
}
