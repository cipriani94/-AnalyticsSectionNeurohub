<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CreateRequestShare extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //Qui in teoria in base al sito che si prende avrà la sua chiave
        $website = DB::table('websites')->select('Authorization')->where('domain',request()->baseUrl())->first();
        if(empty($website))
        {
            return false;
        }
        //'~TG6Qu,K73Tc8f+ZZKQAAV~+)rfz^t)nd1z#wehbM'
        return md5($website->authorization) == request()->header('Authorization');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'attivita_id' => 'required|integer',
            'extra' => 'required|array',
            'extra.name' => 'required|string',
            'extra.userName' => 'required|string',
            'extra.email' => 'required|string',
            'extra.allegato' => 'required|string',
            'extra.userLink' => 'required|string',
            'allegato_id' => 'required|integer'
        ];
    }
}
