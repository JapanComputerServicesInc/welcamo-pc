<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateHistoryRequest extends FormRequest
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
            'id'                => 'bail|required|exists:histories,id',
            'reception_user_id' => 'bail|required|exists:users,id',
            'company_name'      => 'bail|required|max:60',
            'visitor_name'      => 'bail|required|max:20',
            'purpose_id'        => 'bail|required|exists:purposes,id',
            'purpose_remarks'   => 'bail|max:400',
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
            'company_name'    => __('app.company_name'),
            'visitor_name'    => __('app.visitor_name'),
            'purpose_remarks' => __('app.purpose_remarks'),
        ];
    }
}
