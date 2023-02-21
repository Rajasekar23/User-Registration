<?php

namespace App\Core;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * {@inheritdoc}
 *
 * Adding some additional methods for quering
 */
abstract class CoreModel extends Model
{
    use SoftDeletes;

    /**
     * Provide a default Datetime format for all columns.
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s.uP';


    /**
     * Used to set the default date time format.
     *
     * @todo Remove this function to take effect of the derived ones
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat; // 'Y-m-d\TH:i:sT';
    }

    /**
     * Save a new model and return the instance.
     *
     * @param array $attributes data that need to create as new model
     *
     * @return CoreModel
     */
    public static function create(array $attributes)
    {
        $model = new static($attributes);

        $model->save();

        return $model;
    }

    /**
     * Begin querying the model.
     *
     * @return CoreQueryBuilder|CoreEloquentBuilder
     */
    public static function query()
    {
        return parent::query();
    }


}
