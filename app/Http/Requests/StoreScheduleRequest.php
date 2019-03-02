<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreScheduleRequest extends FormRequest
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
            'schedule_date' => 'bail|required|date',
            'company_name'  => 'bail|required|max:60',
            'visitor_name'  => 'bail|required|max:20',
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
            'schedule_date' => __('app.schedule_date'),
            'company_name'  => __('app.company_name'),
            'visitor_name'  => __('app.visitor_name'),
        ];
    }
}
