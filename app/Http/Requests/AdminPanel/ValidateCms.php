<?php

namespace App\Http\Requests\AdminPanel;

use Illuminate\Foundation\Http\FormRequest;

class ValidateCms extends FormRequest
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
        if (is_null(request('cms_id'))) {
            return [
                'title' => 'required',
                'sub_title' => 'required',
                'page_type' => 'required',
                'description' => 'required',
                'image' => 'required',
            ];
        } else {
            $id = dv(request('cms_id'));
            return [
                'title' => 'required',
                'sub_title' => 'required',
                'type' => 'required',
                'description' => 'required',
                'image' => 'required',
            ];
        }
    }
}
