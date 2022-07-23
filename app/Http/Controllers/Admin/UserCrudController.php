<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\FactoryAdminController\AbstractCrudController;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Jobs\User\NewUserRegistrationJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserCrudController extends AbstractCrudController
{

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'name' => 'name',
                'label' => __($this->getResourcesLang() . '.listOperation.label.name'),
                'type' => 'text',
            ],
            [
                'name' => 'email',
                'label' => __($this->getResourcesLang() . '.listOperation.label.email'),
                'type' => 'email',
            ],

        ]);


    }

    protected function setupCreateOperation()
    {
        $this->addUserFields();
        $this->crud->setValidation(StoreRequest::class);
    }
    public function setupUpdateOperation()
    {
        $this->addUserFields();
        $this->crud->setValidation(UpdateRequest::class);
    }

    protected function hasActiveAttribute(): bool
    {
        return false;
    }
    protected function manageCreateItem($dataRequest): Model
    {
        $clear_password = $dataRequest['password_confirmation'];
        unset($dataRequest['password_confirmation']);
        $obj = $this->crud->create($dataRequest);
        if (!empty($obj)) {
            dispatch(new NewUserRegistrationJob($obj->id, $clear_password))->onConnection("database")->delay(now()->addSeconds());
        }
        return $obj;
    }

    /**
     * Update the specified resource in the database.
     *
     * @return array|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update()
    {
        $this->crud->setRequest($this->crud->validateRequest(true));
        $this->crud->setRequest($this->handlePasswordInput($this->crud->getRequest()));
        $this->crud->unsetValidation(); // validation has already been run

        return parent::update();
    }
    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        //$request->request->remove('password_confirmation');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }
    protected function addUserFields(bool $editMode = false)
    {
        $operation = $editMode ? 'updateOpration' : 'createOperation';
        $this->crud->addFields([
            [
                'name' => 'name',
                'label' => __($this->getResourcesLang() . '.'.$operation.'.label.name'),
                'type' => 'text',
                'attributes' => [
                    'placeholder' => __($this->getResourcesLang() . '.'.$operation.'.placeholder.name'),
                ]
            ],
            [
                'name' => 'email',
                'label' => __($this->getResourcesLang() . '.'.$operation.'.label.email'),
                'type' => 'email',
                'attributes' => [
                    'placeholder' => __($this->getResourcesLang() . '.'.$operation.'.placeholder.email'),
                ]
            ],
            [
                'name' => 'password',
                'label' => __($this->getResourcesLang() . '.'.$operation.'.label.password'),
                'type' => 'password',
                'attributes' => [
                    'placeholder' => __('userpage.placeholder.password'),
                ]
            ],
            [
                'name' => 'password_confirmation',
                'label' => __($this->getResourcesLang() . '.'.$operation.'.label.password_confirmation'),
                'type' => 'password',
                'attributes' => [
                    'placeholder' => __($this->getResourcesLang() . '.'.$operation.'.placeholder.password_confirmation'),
                ]
            ]
        ]);
    }

    protected function getSectionName(): string
    {
        return 'user';
    }

    protected function getModelValue(): string
    {
        return \App\Models\User::class;
    }

    protected function getModelTableName(): string
    {
        return User::getTableName();
    }

    protected function updateCompanyParentClass(): bool
    {
        return false;
    }
    protected function deleteElementsIsClass(): bool
    {
        return true;
    }

}
