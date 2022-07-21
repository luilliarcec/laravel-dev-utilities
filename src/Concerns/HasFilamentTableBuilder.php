<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Support\Arr;
use Luilliarcec\DevUtilities\DataProviders\Filter;

trait HasFilamentTableBuilder
{
    public function assertFilterData(Filter $filter, mixed $component, mixed $filterable): void
    {
        $filter->init($filterable);

        $component->set($filter->getName(), $filter->getValue());

        foreach (Arr::wrap($filter->getVisibleRecords()) as $value) {
            $component->assertSee($value);
        }

        foreach ($filter->getDontVisibleRecords() as $value) {
            $component->assertDontSee($value);
        }
    }
}
