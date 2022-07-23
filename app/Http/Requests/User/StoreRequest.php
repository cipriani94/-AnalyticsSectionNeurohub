<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:5|max:255',
            'email' => 'required|email|min:3|max:255|unique:users,email',
            'password' => 'required|confirmed',
        ];
    }

    public function attributes()
    {
        return [
            'name' => __('userpage.requestMessage.name')
        ]; 
    }
}
