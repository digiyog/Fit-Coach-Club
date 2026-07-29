<?php

namespace App\Http\Requests\AdminPanel;

use Illuminate\Foundation\Http\FormRequest;

class ValidateSuperAdmin extends FormRequest
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
        if (is_null(request('user_id'))) {
            return [
                'name' => 'required',
                'email' => 'required|unique:users,email,NULL,id',
                'mobile_number' => 'required|unique:users,mobile_number,NULL,id,role_name,super-admin',
            ];
        } else {
            $id = dv(request('user_id'));
            return [
                'name' => 'required',
                'email' => 'required|unique:users,email,'.$id,
                'mobile_number' => 'required|unique:users,mobile_number,'.$id
            ];
        }
    }
}
