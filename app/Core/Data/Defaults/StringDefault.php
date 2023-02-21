<?php

namespace App\Core\Data\Defaults;

use Carbon\Carbon;
use Spatie\LaravelData\Support\DataProperty;

/**
 * Services.
 */
class StringDefault implements DefaultValue
{
     /**
     * Cast as slug
     *
     * @param DataProperty $property
     * @param mixed        $value
     * @param array        $context
     *
     * @return String
     */
    public function value(DataProperty $property, mixed $value, array $context): mixed
    {
        if (empty($value)) {
            return '';
        }

        return $value;
    }
}
