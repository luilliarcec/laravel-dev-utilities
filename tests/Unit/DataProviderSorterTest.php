<?php

namespace Tests\Unit;

use Exception;
use Luilliarcec\DevUtilities\Concerns\HasSpatieQueryBuilder;
use Luilliarcec\DevUtilities\DataProviders\Sorter;
use Tests\TestCase;
use Tests\Utils\User;

class DataProviderSorterTest extends TestCase
{
    use HasSpatieQueryBuilder;

    public function test_that_the_data_is_accessible()
    {
        $provider = Sorter::make('first_name')
            ->orderedRecords(['Luis', 'Carlos', 'Andres']);

        $this->assertEquals('first_name', $provider->getName());
        $this->assertEquals(['Luis', 'Carlos', 'Andres'], $provider->getOrderedRecords());
    }

    public function test_that_seed_is_executed_in_the_init_function()
    {
        $this->expectExceptionMessage('Faker exception caused by seed function called on init function');

        $provider = Sorter::make('first_name')
            ->orderedRecords(['Luis', 'Carlos', 'Andres'])
            ->seeder(fn() => throw new Exception('Faker exception caused by seed function called on init function'));

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
                Sorter::make('name')
                    ->orderedRecords(['ANDRES', 'BEN', 'CARLOS']),
            ],

            'sort by asc -name' => [
                Sorter::make('name')
                    ->orderedRecords(['CARLOS', 'BEN', 'ANDRES'])
                    ->desc(),
            ],
        ];
    }
}
