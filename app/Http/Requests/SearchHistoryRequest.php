<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SearchHistoryRequest extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        return [
            'year'     => 'bail|digits:4',
            'month'    => 'bail|digits_between:1,2|between:1,12',
            'criteria' => 'bail|max:20',
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
            'year'     => __('app.year'),
            'month'    => __('app.month'),
            'criteria' => __('app.criteria'),
        ];
    }
}
