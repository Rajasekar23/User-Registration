<?php

namespace App\Core;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * {@inheritdoc}
 *
 * Used to add some additional workouts
 */
abstract class CoreRepository extends BaseRepository
{

    /**
     * Hold the Model for the repository.
     *
     * @var CoreModel
     */
    protected $model;

    /**
     * Used to append created_by and respective company id.
     *
     * @param mixed $input data that need to be insert in the model
     */
    public function create($input)
    {
        $auth = Auth::user();
        if ($auth !== null) {
            $input['created_by'] = $this->checkOrDefault($input, 'created_by', $auth->id);
        }

        return parent::create($input);
    }

    /**
     * Used to append updated_by while update model.
     *
     * @param mixed $input data that need to be update in the model
     * @param mixed $id    data that which record need to be update in the model
     */
    public function update($input, $id)
    {
        $auth = Auth::user();
        if ($auth !== null) {
            $input['updated_by'] = $this->checkOrDefault($input, 'updated_by', $auth->id);
        }
        return parent::update($input, $id);
    }

    /**
     * Get the Database table name of the related model.
     *
     * @return string
     */
    public function getSqlTableName()
    {
        return $this->model->getTable();
    }



    /**
     * Get basic Filter query.
     *
     * @param mixed   $tablename -
     *
     * @return CoreQueryBuilder
     */
    public function getBasicFilterQuery($tablename = '')
    {
        $colName = empty($tablename) ? 'deleted_at' : "{$tablename}.deleted_at";

        $query = $this->model->query()
            ->whereNull($colName)
        ;
        return $query;
    }

    public function paginate($limit = null, $columns = ['*'], $method = 'paginate')
    {
        $params = request()->all();
        $limit = $params['limit'] ?? null;
        // $offset = $params['offset']??null;
        return parent::paginate($limit, $columns);
    }

    /**
     * Used to check empty if empty return default otherwise return the object.
     *
     * @param mixed $obj     The object that need to check
     * @param mixed $field   If it's an array use the field
     * @param mixed $default The default value if $object is empty or not set
     *
     * @return $obj or $obj[$field] or $default
     */
    function checkOrDefault($obj, $field, $default = null)
    {
        if (!empty($field)) {
            return (isset($obj[$field]) && !empty($obj[$field])) ? $obj[$field] : $default;
        }

        return (isset($obj) && !empty($obj)) ? $obj : $default;
    }


}
