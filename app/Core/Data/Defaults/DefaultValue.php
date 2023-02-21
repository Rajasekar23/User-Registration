<?php

namespace App\Core\Data\Defaults;

use Spatie\LaravelData\Support\DataProperty;

/**
 * Services.
 */
interface DefaultValue
{
    public function value(DataProperty $property, mixed $value, array $context): mixed;
}
