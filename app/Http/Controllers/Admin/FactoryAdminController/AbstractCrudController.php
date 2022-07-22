<?php


namespace App\Http\Controllers\Admin\FactoryAdminController;


use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\CrudPanel\Traits\Search;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use ReflectionException;
use Illuminate\Support\Str;
abstract class AbstractCrudController extends CrudController
{
    use ListOperation {
        search as protected traitSearch;
    }
    use CreateOperation {
        store as traitStore;
    }
    use UpdateOperation {
        update as traitUpdate;
        edit as traitEdit;
    }
    use DeleteOperation {
        destroy as traitDestroy;
    }
    use ShowOperation {
        show as traitShow;
    }
    use Search {
        getRowViews as traitGetRowViews;
    }

    protected $updateFailErrors;
    protected $internalSaveActionManagement;
    protected $editMode;
    public function __construct()
    {
        parent::__construct();
        $this->updateFailErrors = array();
        $this->internalSaveActionManagement = array();
        $this->editMode = false;
    }

    protected abstract function setupListOperation();

    protected abstract function setupCreateOperation();

    protected abstract function getEntryNameStrings(): array;

    protected abstract function hasActiveAttribute(): bool;

    protected abstract function getSectionName(): string;

    protected abstract function getModelValue(): string;

    protected abstract function getModelTableName(): string;

    protected abstract function updateCompanyParentClass(): bool;



    protected function getResourcesLang(): string
    {
        return str_replace(['-', '_'], '', $this->getSectionName()) . 'page';
    }

    protected function modifyNewEntryCreated($item)
    {
    }

    protected function manageCreateItem($dataRequest): Model
    {
        return $this->crud->create($dataRequest);
    }


    protected function factoryStore($dataRequest)
    {
        $item = $this->manageCreateItem($dataRequest);
        if (!empty($item)) {
            $this->modifyNewEntryCreated($item);
            $this->data['entry'] = $this->crud->entry = $item;
            \Alert::success(__($this->getResourcesLang() . '.createMessage.createSuccess'))->flash();
        }
        return $item;
    }

    protected function setupButtonsVisibility()
    {
        foreach (['show', 'create', 'update', 'delete', 'list'] as $value) {
            if (!$this->crud->hasAccess($value)) {
                $this->crud->removeButton($value);
            }
        }
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->editMode = true;
        $this->setupCreateOperation();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false);
    }

    protected function manageUpdateItem($request, $dataRequest): object
    {
        return $this->crud->update($request->get($this->crud->model->getKeyName()), $dataRequest);
    }



    protected function invokeMethod($action, $request, $dataRequest)
    {
        $this->internalSaveActionManagement[$action]($request, $dataRequest);
    }

    /**
     * The search function that is called by the data table.
     *
     * @return array JSON Array of cells in HTML form.
     * @throws ReflectionException
     */
    public function search(): array
    {
        $this->crud->hasAccessOrFail('list');

        $this->crud->applyUnappliedFilters();

        $totalRows = $this->crud->model->count();
        $filteredRows = $this->crud->query->toBase()->getCountForPagination();
        $startIndex = request()->input('start') ?: 0;
        // if a search term was present
        if (request()->input('search') && request()->input('search')['value']) {
            // filter the results accordingly
            $this->crud->applySearchTerm(request()->input('search')['value']);
            // recalculate the number of filtered rows
            $filteredRows = $this->crud->count();
        }
        // start the results according to the datatables pagination
        if (request()->input('start')) {
            $this->crud->skip((int)request()->input('start'));
        }
        // limit the number of results according to the datatables pagination
        if (request()->input('length')) {
            $this->crud->take((int)request()->input('length'));
        }
        // overwrite any order set in the setup() method with the datatables order
        if (request()->input('order')) {
            // clear any past orderBy rules
            $this->crud->query->getQuery()->orders = null;
            foreach ((array)request()->input('order') as $order) {
                $column_number = (int)$order['column'];
                $column_direction = (strtolower((string)$order['dir']) == 'asc' ? 'ASC' : 'DESC');
                $column = $this->crud->findColumnById($column_number);
                if ($column['tableColumn'] && !isset($column['orderLogic'])) {
                    // apply the current orderBy rules
                    $this->crud->orderByWithPrefix($column['name'], $column_direction);
                }

                // check for custom order logic in the column definition
                if (isset($column['orderLogic'])) {
                    $this->crud->customOrderBy($column, $column_direction);
                }
            }
        }

        // show newest items first, by default (if no order has been set for the primary column)
        // if there was no order set, this will be the only one
        // if there was an order set, this will be the last one (after all others were applied)
        // Note to self: `toBase()` returns also the orders contained in global scopes, while `getQuery()` don't.
        $orderBy = $this->crud->query->toBase()->orders;
        $table = $this->crud->model->getTable();
        $key = $this->crud->model->getKeyName();

        $hasOrderByPrimaryKey = collect($orderBy)->some(function ($item) use ($key, $table) {
            return (isset($item['column']) && $item['column'] === $key)
                || (isset($item['sql']) && str_contains($item['sql'], "$table.$key"));
        });

        if (!$hasOrderByPrimaryKey) {
            $this->crud->orderByWithPrefix($this->crud->model->getKeyName(), 'DESC');
        }

        $entries = $this->crud->getEntries();


        return $this->crud->getEntriesAsJsonForDatatables($entries, $totalRows, $filteredRows, $startIndex);
    }

    private function methodForDemo(string $messageFolder): RedirectResponse
    {
        \Alert::success(__($this->getResourcesLang() . $messageFolder))->flash();
        return redirect()->route(str_replace('_', '-', $this->getSectionName()) . '.index');
    }

    /**
     * Store a newly created resource in the database.
     *
     * @return array|RedirectResponse|Response
     */


    public function store()
    {
        $this->crud->hasAccessOrFail('create');
        // execute the FormRequest authorization and validation, if one is requiret
        $request = $this->crud->validateRequest();

        // insert item in the db
        $dataRequest = $this->crud->getStrippedSaveRequest($request);
        $item = $this->factoryStore($dataRequest);
        // save the redirect choice for next time
        $this->crud->setSaveAction('save_and_back');
        return $this->crud->performSaveAction($item->getKey());
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');
        $req = $this->crud->getRequest();
        $req->request->remove('password_confirmation');
        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // insert item in the db
        $dataRequest = $this->crud->getStrippedSaveRequest($request);
        $action = $this->crud->getRequest()->input('save_action');
        if (array_key_exists($action, $this->internalSaveActionManagement)) {
            $item = $this->crud->getCurrentEntry();
            $this->invokeMethod($action, $request, $dataRequest);
        } else {

            // update the row in the db
            $item = $this->manageUpdateItem($request, $dataRequest);
            if (empty($this->updateFailErrors)) {
                $this->data['entry'] = $this->crud->entry = $item;
                // show a success message
                \Alert::success(trans('backpack::crud.update_success'))->flash();
            }
        }
        if (!empty($this->updateFailErrors)) {
            //Gestire la traduzione
            \Alert::error(__($this->updateFailErrors[0] ?? 'devicepage.alert.update_fail'))->flash();
            unset($this->updateFailErrors[0]);
        }
        // save the redirect choice for next time
        $this->crud->setSaveAction('save_and_back');
        return $this->crud->performSaveAction($item->getKey());
    }

    public function destroy($id)
    {
        $item = $this->crud->getModel()->findOrFail($id);
        if ($this->deleteElementsIsClass())
        {
            return $this->crud->delete($id);
        }
        $item->is_active = false;
        $item->save();
        return !empty($item);
    }

    public function setup()
    {
        CRUD::setModel($this->getModelValue());
        CRUD::setEntityNameStrings($this->getEntryNameStrings()[0], $this->getEntryNameStrings()[1]);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/' . str_replace('_', '-', $this->getSectionName()));

        $this->crud->orderBy($this->getModelTableName() . '.id');
        $this->crud->addClause('select', $this->getModelTableName() . '.*');
        if ($this->hasActiveAttribute()) {
            $this->crud->addClause('where', $this->getModelTableName() . '.is_active', true);
        }
        $this->crud->denyAccess(['show']);
        $this->crud->setActionsColumnPriority(10000);
    }


    protected function deleteElementsIsClass(): bool
    {
        return false;
    }

}
