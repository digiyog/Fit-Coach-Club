<?php

namespace App\Http\Requests\AdminPanel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfile extends FormRequest
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
            'name' => 'required',
            'mobile_number' => [
                Rule::unique('users')->ignore($user->id)->where(function ($query) {
                    $query->where('deleted_at', '=', null);
                }),
            ],
            'email' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif,svg|max:5000',
        ];
    }
}
