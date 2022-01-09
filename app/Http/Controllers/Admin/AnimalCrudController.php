<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\AnimalRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\DB;

/**
 * Class AnimalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class AnimalCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Animal::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/animal');
        CRUD::setEntityNameStrings('animal', 'animals');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('image')->type('image');
        CRUD::column('name');
        CRUD::column('breed');
        CRUD::column('birth_date');
        CRUD::column('type');
        CRUD::column('created_at');
        CRUD::column('updated_at');
        // $this->crud->set('show.setFromDb', false);
        // $this->crud->addColumns($this->getFieldsData(TRUE));

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(AnimalRequest::class);

        CRUD::field('id') -> type("hidden");
        CRUD::field('name');
        CRUD::field('breed')->type('select2_from_array')->options($this->getAllBreeds());
        CRUD::field('birth_date');
        CRUD::field('type');
        CRUD::field('created_at')-> type("hidden");
        CRUD::field('updated_at')-> type("hidden");
        CRUD::field('image')->type('image');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    protected function getAllBreeds()
    {
        $breeds = [];
        $breeds = ['Select breed'];
        foreach (DB::table('breeds')->get() as $breed) {
            $breeds[$breed->id] = $breed->name;
        }

        return $breeds;
    }
}
