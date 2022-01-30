<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Testing\TestResponse;
use Luilliarcec\DevUtilities\DataProviders\Filters;

trait HasFilters
{
    public function assertFilterData(string $uri, Filters $filter, mixed $filterable): void
    {
        $filter->init($filterable);

        $response = $this->get("$uri?$filter->parameters")->assertSuccessful();

        if ($filter->bag) {
            $this->assertDataFilteredByBag($response, $filter);
        } else {
            $this->assertDataFilteredBySee($response, $filter);
        }
    }

    protected function assertDataFilteredByBag(TestResponse $response, Filters $filter)
    {
        $this->assertTrue(
            $response->original->{$filter->bag}->where($filter->field, $filter->see)->isNotEmpty()
        );

        $this->assertTrue(
            $response->original->{$filter->bag}->whereIn($filter->field, $filter->dontSee)->isEmpty(),
        );
    }

    protected function assertDataFilteredBySee(TestResponse $response, Filters $filter)
    {
        $response->assertDontSee($filter->dontSee)->assertSee($filter->see);
    }
}
