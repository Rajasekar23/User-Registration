<?php

namespace App\Core\Data\Casters;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

/**
 * CarbonCaster class.
 */
class CarbonCaster implements Cast
{
    /**
     * Cast as slug
     *
     * @param DataProperty $property
     * @param array        $context
     *
     * @return String
     */
    public function cast(DataProperty $property,  $value, array $context)
    {
        return Carbon::parse($value);
    }
}
