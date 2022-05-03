<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Support\Arr;
use Luilliarcec\DevUtilities\DataProviders\Filter;

trait HasFilamentTableBuilder
{
    public function assertFilterData(Filter $filter, mixed $component, mixed $filterable): void
    {
        $filter->init($filterable);

        $component->set($filter->filter, $filter->value);

        foreach (Arr::wrap($filter->see) as $value) {
            $component->assertSee($value);
        }

        foreach ($filter->dontSee as $value) {
            $component->assertDontSee($value);
        }
    }
}
