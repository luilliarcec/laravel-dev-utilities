<?php

namespace Tests\Unit;

use Exception;
use Luilliarcec\DevUtilities\Concerns\HasSorters;
use Luilliarcec\DevUtilities\DataProviders\Sorters;
use Tests\TestCase;
use Tests\Utils\User;

class DataProviderSorterTest extends TestCase
{
    use HasSorters;

    public function test_that_the_data_is_accessible()
    {
        $provider = new Sorters(
            sort: 'first_name', data: ['Luis', 'Carlos', 'Andres'],
        );

        $this->assertEquals('first_name', $provider->sort);
        $this->assertEquals(['Luis', 'Carlos', 'Andres'], $provider->data);
    }

    public function test_that_seed_is_executed_in_the_init_function()
    {
        $this->expectExceptionMessage('Faker exception caused by seed function called on init function');

        $provider = new Sorters(
            sort: 'first_name', data: ['Luis', 'Carlos', 'Andres'],
            seed: fn () => throw new Exception('Faker exception caused by seed function called on init function')
        );

        $provider->init(false);
    }

    /** @dataProvider sorts */
    public function test_sort_data($sorter)
    {
        $this->assertSortData(uri: '/sorts', sorter: $sorter, sortable: fn ($data) => User::create($data));
    }

    public function sorts(): array
    {
        return [
            'sort by asc name' => [
                new Sorters(sort: 'name', data: ['ANDRES', 'BEN', 'CARLOS']),
            ],

            'sort by asc -name' => [
                new Sorters(sort: '-name', data: ['CARLOS', 'BEN', 'ANDRES']),
            ],

            'sort by asc name with values' => [
                new Sorters(sort: 'name', data: ['andres', 'ben', 'carlos'], values: ['andres', 'ben', 'carlos']),
            ],
        ];
    }
}
