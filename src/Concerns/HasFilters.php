<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Testing\TestResponse;
use Luilliarcec\DevUtilities\DataProviders\Filter;

trait HasFilters
{
    public function assertFilterData(string $uri, Filter $filter, mixed $filterable): void
    {
        $filter->init($filterable);

        $response = $this->get("$uri?$filter->parameters")->assertSuccessful();

        if ($filter->bag) {
            $this->assertDataFilteredByBag($response, $filter);
        } else {
            $this->assertDataFilteredBySee($response, $filter);
        }
    }

    protected function assertDataFilteredByBag(TestResponse $response, Filter $filter)
    {
        $this->assertTrue(
            $response->original->{$filter->bag}->where($filter->field, $filter->see)->isNotEmpty()
        );

        $this->assertTrue(
            $response->original->{$filter->bag}->whereIn($filter->field, $filter->dontSee)->isEmpty(),
        );
    }

    protected function assertDataFilteredBySee(TestResponse $response, Filter $filter)
    {
        $response->assertDontSee($filter->dontSee)->assertSee($filter->see);
    }
}
