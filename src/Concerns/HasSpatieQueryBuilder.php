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

        $query = $this->queryBuilderForFilters($filter->filter, $filter->value);

        $response = $this->get("$uri?$query")->assertSuccessful();

        if ($filter->bag) {
            $this->assertTrue(
                $response->original->{$filter->bag}
                    ->where($filter->field, $filter->see)
                    ->isNotEmpty()
            );

            $this->assertTrue(
                $response->original->{$filter->bag}
                    ->whereIn($filter->field, $filter->dontSee)
                    ->isEmpty(),
            );
        } else {
            $response->assertDontSee($filter->dontSee)
                     ->assertSee($filter->see);;
        }
    }

    private function queryBuilderForFilters($filter, mixed $value): string
    {
        $parameter = config('query-builder.parameters.filter', 'filter');

        return collect($value)
            ->transform(function ($item) use ($parameter, $filter, $value) {
                if (is_array($value)) {
                    return sprintf('%s[%s][]=%s', $parameter, $filter, $item);
                }

                return sprintf('%s[%s]=%s', $parameter, $filter, $item);
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
