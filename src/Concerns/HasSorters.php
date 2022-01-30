<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Testing\TestResponse;
use Luilliarcec\DevUtilities\DataProviders\Sorters;

trait HasSorters
{
    public function assertSortData(string $uri, Sorters $sorter, mixed $sortable): TestResponse
    {
        $sorter->init($sortable);

        return $this->get("$uri?$sorter->parameters")
            ->assertSeeInOrder($sorter->data);
    }
}
