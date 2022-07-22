<?php


namespace App\Http\Controllers\Admin\ApiController;


use App\Http\Requests\Api\CreateRequestShare;
use App\Models\Linkedin\RequestShare;

class ApiRequestShareController extends AbstractApiController
{

    protected function getModelValue()
    {
        return new RequestShare();
    }

    public function createRequestShare(CreateRequestShare $request)
    {
        foreach($this->model->toArray() as $attributes)
        {
            if($request->has($attributes))
            {
                $this->model->{$attributes} = $request[$attributes];
            }
            $this->model->save();
            return json_encode('OK');
        }
    }
}
