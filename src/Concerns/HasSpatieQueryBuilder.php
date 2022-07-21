<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Testing\TestResponse;
use Luilliarcec\DevUtilities\DataProviders\Filter;
use Luilliarcec\DevUtilities\DataProviders\Sorter;

trait HasSpatieQueryBuilder
{
    public function assertFilterData(string $uri, Filter $filter, mixed $filterable): void
    {
        $filter->init($filterable);

        $query = $this->queryBuilderForFilters($filter->getName(), $filter->getValue());

        $response = $this->get("$uri?$query")->assertSuccessful();

        if ($bag = $filter->getBag()) {
            $this->assertTrue(
                $response->original->{$bag}
                    ->where($filter->getField(), $filter->getVisibleRecords())
                    ->isNotEmpty()
            );

            $this->assertTrue(
                $response->original->{$bag}
                    ->whereIn($filter->getField(), $filter->getDontVisibleRecords())
                    ->isEmpty(),
            );
        } else {
            $response->assertDontSee($filter->getDontVisibleRecords())
                     ->assertSee($filter->getVisibleRecords());
        }
    }

    private function queryBuilderForFilters($name, mixed $value): string
    {
        $parameter = config('query-builder.parameters.filter', 'filter');

        return collect($value)
            ->transform(function ($item) use ($parameter, $name, $value) {
                if (is_array($value)) {
                    return sprintf('%s[%s][]=%s', $parameter, $name, $item);
                }

                return sprintf('%s[%s]=%s', $parameter, $name, $item);
            })
            ->implode('&');
    }

    public function assertSortData(string $uri, Sorter $sorter, mixed $sortable): TestResponse
    {
        $sorter->init($sortable);

        return $this->get("$uri?$sorter->parameters")
                    ->assertSeeInOrder($sorter->data);
    }
}
