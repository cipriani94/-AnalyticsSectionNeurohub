<?php

namespace App\Http\Requests\User;


class UpdateRequest extends StoreRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = $this->get('id') ?? request()->route('id');
        $rules = parent::rules();
        $rules['password'] = 'confirmed';
        $rules['email'] = 'required|unique:users,email,' . $id;
        return $rules;
    }


}
