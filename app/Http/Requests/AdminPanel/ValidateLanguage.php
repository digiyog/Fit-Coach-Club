<?php

namespace App\Http\Requests\AdminPanel;

use Illuminate\Foundation\Http\FormRequest;

class ValidateLanguage extends FormRequest
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
        // Get users
        $authUser = auth()->user();
        //----------
        if (is_null(request('language_id'))) {
            return [
                'name' => 'required|unique:languages,name,NULL,id',
                'code' => 'required|unique:languages,code,NULL,id',
            ];
        } else {
            $id = dv(request('language_id'));
            return [
                'name' => 'required|unique:languages,name,'.$id,
                'code' => 'required|unique:languages,code,'.$id,
            ];
        }
    }
}
