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

        $response = $this->get("$uri?$filter->parameters")->assertSuccessful();

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

    public function assertSortData(string $uri, Sorter $sorter, mixed $sortable): TestResponse
    {
        $sorter->init($sortable);

        return $this->get("$uri?$sorter->parameters")
                    ->assertSeeInOrder($sorter->data);
    }
}
