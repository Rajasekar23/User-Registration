<?php

namespace App\Core\Data;

use App\Core\Data\Pipes\DefaultvaluePipe;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataPipeline;

/**
 * CustomDto class.
 */
class CustomDto extends Data
{
    /**
     * Return not null prop values.
     *
     * @return array
     */
    public function notNull()
    {
        $c = collect($this->all())->filter(function ($value) {
            return $value !== null;
        });

        return $c->toArray();
    }


}
