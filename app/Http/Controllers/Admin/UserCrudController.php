<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Requests\UserRequest;
use App\Models\Supermarket;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
use Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanel;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Hash;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use ListOperation;
    use CreateOperation {
        store as traitStore;
    }
    use UpdateOperation {
        update as traitUpdate;
    }
    use DeleteOperation;
    use ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    public function store()
    {
        CRUD::setRequest(CRUD::validateRequest());
        CRUD::setRequest($this->handlePasswordInput(CRUD::getRequest()));
        CRUD::unsetValidation();

        return $this->traitStore();
    }

    protected function handlePasswordInput($request)
    {
        // Remove fields not present on the user.
        $request->request->remove('password_confirmation');
        $request->request->remove('roles_show');
        $request->request->remove('permissions_show');

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        return $request;
    }

    public function update()
    {
        CRUD::setRequest(CRUD::validateRequest());
        CRUD::setRequest($this->handlePasswordInput(CRUD::getRequest()));
        CRUD::unsetValidation(); // validation has already been run
        return $this->traitUpdate();
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Nome',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'role',
            'label' => 'Tipo de Usuário',
            'type' => 'enum',
            'enum' => UserRole::class,
        ]);

        CRUD::addColumn([
            'name' => 'supermarket',
            'label' => 'Supermercado',
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->supermarket->name;
            },
            'wrapper' => [
//                'href' => function ($entry) {
//                    return backpack_url('supermarket/' . $entry->supermarket->id . '/edit');
//                },
                'class' => 'badge badge-default',
            ],
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->userFields();
        CRUD::setValidation(UserRequest::class);
    }

    private function userFields()
    {
        CRUD::addField([
            'name' => 'name',
            'label' => 'Nome',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'email',
            'label' => 'Email',
            'type' => 'email',
        ]);

        CRUD::addField([
            'name' => 'password',
            'label' => 'Senha',
            'type' => 'password',
        ]);

        CRUD::addField([
            'name' => 'password_confirmation',
            'label' => 'Confirmação de Senha',
            'type' => 'password',
        ]);

        CRUD::addField([
            'name' => 'role',
            'label' => 'Tipo de Usuário',
            'type' => 'select_from_array',
            'options' => UserRole::options(),
            'allows_null' => false,
        ]);

        CRUD::addField([
            'name' => 'supermarket_id',
            'label' => 'Supermercado',
            'type' => 'select_from_array',
            'options' => Supermarket::all()->pluck('name', 'id')->toArray(),
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        $this->userFields();
        CRUD::setValidation(UserRequest::class);


        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }
}
