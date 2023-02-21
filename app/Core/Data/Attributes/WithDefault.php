<?php

namespace App\Core\Data\Attributes;

use App\Core\Data\Defaults\DefaultValue;
use Attribute;
use Spatie\LaravelData\Exceptions\CannotCreateDefaultAttribute;

/**
 * Services.
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class WithDefault
{
    public array $arguments;

    public function __construct(
        /** @var class-string<App\Core\Data\Defaults\DefaultValue> $defClass */
        public string $defClass,
        mixed ...$arguments
    ) {

        $this->arguments = $arguments;
    }

    /**
     * Get Default value class.
     */
    public function get(): DefaultValue
    {
        return new ($this->defClass)(...$this->arguments);
    }
}
