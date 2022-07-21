<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Support\Arr;
use Luilliarcec\DevUtilities\DataProviders\Filter;
use Luilliarcec\DevUtilities\DataProviders\Sorter;

trait HasFilamentTableBuilder
{
    public function assertFilterData(Filter $filter, mixed $component, mixed $filterable): void
    {
        $filter->init($filterable);

        $name = ($name = $filter->getName()) == 'tableSearchQuery'
            ? $name
            : 'tableFilters.'.$name;

        $component->set($name, $filter->getValue());

        foreach (Arr::wrap($filter->getVisibleRecords()) as $value) {
            $component->assertSee($value);
        }

        foreach ($filter->getDontVisibleRecords() as $value) {
            $component->assertDontSee($value);
        }
    }

    public function assertSortData(Sorter $sorter, mixed $component, mixed $sorteable): void
    {
        $sorter->init($sorteable);

        $component->set('tableSortColumn', $sorter->getName());
        $component->set('tableSortDirection', $sorter->getOrderDirection());
        $component->assertSeeInOrder($sorter->getOrderedRecords());
    }
}
