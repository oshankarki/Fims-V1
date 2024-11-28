<?php

/** --------------------------------------------------------------------------------
 * This repository class manages all the data absctration for categories
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Repositories;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Log;

class WarehouseRepository
{

    /**
     * The category repository instance.
     */
    protected $warehouse;

    /**
     * Inject dependecies
     */
    public function __construct(Warehouse $warehouses)
    {
        $this->warehouse = $warehouses;
    }

    /**
     * get all warehouses on a given type
     * @param string $type the type of the category
     * @return object
     */
    public function get($type = '')
    {

        //new object
        $query = $this->warehouse->newQuery();
        return $query->get();
    }

    /**
     * Search model
     * @param int $id optional for getting a single, specified record
     * @return object category collection
     */

    public function search($id = '')
    {
        $query = $this->warehouse->newQuery();

        $query->selectRaw('(SELECT COUNT(*) FROM warehouses WHERE id = warehouses.id) AS count_warehouses');

        // $query->where('id', $id);

        return $query->get();
    }

    /**
     * Create a new record
     * @return bool process outcome
     */
    public function create(Request $request)
    {
        $warehouse = new $this->warehouse;

        $warehouse->name = request('name');

        if (!$warehouse->save()) {
            return false;
        }

        return true;
    }

    /**
     * update a record
     * @param int $id record id
     * @return mixed int|bool
     */
    public function update($id)
    {

        $warehouse = $this->warehouse->find($id);

        if (!$warehouse) {
            Log::error("Error Updating Record - Record Not Found", ['process' => '[CategoryRepository]', config('app.debug_ref'), 'function' => __FUNCTION__, 'file' => basename(__FILE__), 'line' => __LINE__, 'path' => __FILE__]);
            return false;
        }

        $warehouse->name = request('name');

        if (!$warehouse->save()) {
            Log::error("Error Updating Record - Database Error", ['process' => '[CategoryRepository]', config('app.debug_ref'), 'function' => __FUNCTION__, 'file' => basename(__FILE__), 'line' => __LINE__, 'path' => __FILE__]);
            return false;
        }

        return true;
    }

    public function delete($id)
    {
        $warehouse = $this->warehouse->find($id);
        if (!$warehouse) {
            Log::error('Error Deleting Record - Record Not Found', ['process' => '[WarehouseRepository]', config('app.debug_ref'), 'function' => __FUNCTION__, 'file' => basename(__FILE__), 'line' => __LINE__, 'path' => __FILE__]);
            return false;
        }

        $warehouse->delete();
        return true;
    }

}