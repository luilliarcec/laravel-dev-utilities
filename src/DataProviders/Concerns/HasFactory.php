<?php

namespace Luilliarcec\DevUtilities\DataProviders\Concerns;

use Closure;

trait HasFactory
{
    protected function factory(mixed $resource, mixed $item)
    {
        if (is_string($resource)) {
            return $resource::factory()->create([$this->getField() => $item]);
        }

        if ($resource instanceof Closure) {
            return $resource([$this->getField() => $item]);
        }

        return $resource;
    }
}
