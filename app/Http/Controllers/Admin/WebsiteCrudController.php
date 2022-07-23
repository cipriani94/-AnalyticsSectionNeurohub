<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\FactoryAdminController\AbstractCrudController;
use App\Http\Requests\WebsiteCreateRequest;
use App\Models\Website;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class WebsiteCrudController extends AbstractCrudController
{
    //
    protected function setupListOperation()
    {
        $this->setupButtonsVisibility();
        $this->crud->addColumns([
            [
                'name' => 'domain',
                'label' => __($this->getResourcesLang() . '.listOperation.label.domain'),
                'type' => 'text',
                'priority' => 1,
            ],
            [
                'name' => 'authorization',
                'label' => __($this->getResourcesLang() . '.listOperation.label.authorization'),
                'type' => 'text',
                'priority' => 2,
            ],
            [
                'name' => 'created_at',
                'label' => __($this->getResourcesLang() . '.listOperation.label.createdAt'),
                'type' => 'text',
                'priority' => 3,
            ],
            [
                'name' => 'updated_at',
                'label' => __($this->getResourcesLang() . '.listOperation.label.updatedAt'),
                'type' => 'text',
                'priority' => 4,
            ],
        ]);
    }

    private function getField()
    {
        return [
            [
                'name' => 'domain',
                'label' => __($this->getResourcesLang() . '.createOperation.label.domain'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __($this->getResourcesLang() . '.createOperation.placeholder.domain'),
                ],
            ],
            [
                'name' => 'authorization',
                'label' => __($this->getResourcesLang() . '.createOperation.label.authorization'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __($this->getResourcesLang() . '.createOperation.placeholder.authorization'),
                ],
            ],
        ];
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(WebsiteCreateRequest::class);

        $this->crud->addFields($this->getField());
    }

    protected function getEntryNameStrings(): array
    {
        return __($this->getResourcesLang().'.entryNameValues');
    }

    protected function hasActiveAttribute(): bool
    {
        return false;
    }

    protected function getSectionName(): string
    {
        return 'website';
    }

    protected function getModelValue(): string
    {
        return \App\Models\Website::class;
    }

    protected function getModelTableName(): string
    {
        return Website::getTableName();
    }

    protected function updateCompanyParentClass(): bool
    {
        return false;
    }
}
