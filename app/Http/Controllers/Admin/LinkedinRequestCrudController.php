<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\FactoryAdminController\AbstractCrudController;
use App\Models\Linkedin\RequestShare;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class LinkedinRequestCrudController extends AbstractCrudController
{
    //
    protected function setupListOperation()
    {
        $this->crud->addColumn([
            [
                'name' => 'website_id',
                'label' => __($this->getResourcesLang() . '.listOperation.label.website'),
                'type' => 'select',
                'entity' => 'website',
                'model' => 'App\Models\Website',
                'attribute' => 'domain',
                'priority' => 1,
            ],
            [
                'name' => 'username',
                'label' => __($this->getResourcesLang() . '.listOperation.label.userName'),
                'type' => 'text',
                'priority' => 2,
            ],
            [
                'name' => 'email',
                'label' => __($this->getResourcesLang() . '.listOperation.label.email'),
                'type' => 'text',
                'priority' => 3,
            ],
            [
                'name' => 'user_link',
                'label' => __($this->getResourcesLang() . '.listOperation.label.userLink'),
                'type' => 'text',
                'priority' => 4,
            ],
            [
                'name' => 'status',
                'label' => __($this->getResourcesLang() . '.listOperation.label.status'),
                'type' => 'text',
                'priority' => 5,
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setFromDb();
    }

    protected function hasActiveAttribute(): bool
    {
        return false;
    }

    protected function getSectionName(): string
    {
        return 'linkedinrequest';
    }

    protected function getModelValue(): string
    {
        return \App\Models\Linkedin\RequestShare::class;
    }

    protected function getModelTableName(): string
    {
        return RequestShare::class;
    }

    protected function updateCompanyParentClass(): bool
    {
        return false;
    }
}
