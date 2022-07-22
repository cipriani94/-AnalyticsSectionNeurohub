<?php


namespace App\Http\Controllers\Admin\ApiController;


use App\Http\Controllers\Controller;

abstract class AbstractApiController extends Controller
{
    protected $model;

    public function __construct()
    {
        $this->model = $this->getModelValue();
    }

    protected abstract function getModelValue();
}
