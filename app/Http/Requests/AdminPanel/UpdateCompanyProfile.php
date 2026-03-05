<?php

namespace App\Http\Requests\AdminPanel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyProfile extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = auth()->user();

        return [
            'company_address' => 'required',
            'company_mobile' => 'required',
            'company_email' => 'required',
            'company_map'   => 'required',
            'company_logo'  => 'mimes:jpeg,png,jpg,gif,svg|max:5000',
            'company_icon'  => 'mimes:jpeg,png,jpg,gif,svg|max:5000',
        ];
    }
}
