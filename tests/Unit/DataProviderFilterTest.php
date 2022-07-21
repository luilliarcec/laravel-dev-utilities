<?php

namespace Tests\Unit;

use Exception;
use Luilliarcec\DevUtilities\Concerns\HasSpatieQueryBuilder;
use Luilliarcec\DevUtilities\DataProviders\Filter;
use Tests\TestCase;
use Tests\Utils\User;

class DataProviderFilterTest extends TestCase
{
    use HasSpatieQueryBuilder;

    public function test_that_the_data_is_accessible()
    {
        $provider = Filter::make('name')
            ->visibleRecords('Luis')
            ->dontVisibleRecords(['Carlos', 'Andres'])
            ->bag('data');

        $this->assertEquals('name', $provider->getField());
        $this->assertEquals('Luis', $provider->getVisibleRecords());
        $this->assertEquals(['Carlos', 'Andres'], $provider->getDontVisibleRecords());
        $this->assertEquals('data', $provider->getBag());
    }

    public function test_that_the_filter_is_used_as_a_field_if_the_field_is_not_sent()
    {
        $provider = Filter::make('name')
            ->visibleRecords('Luis');

        $this->assertEquals('name', $provider->getField());

        $provider = Filter::make('name')
            ->visibleRecords('Luis')
            ->field('filter_name');

        $this->assertEquals('filter_name', $provider->getField());
    }

    public function test_that_seed_is_executed_in_the_init_function()
    {
        $this->expectExceptionMessage('Faker exception caused by seed function called on init function');

        $provider = Filter::make('first_name')
            ->visibleRecords('Luis')
            ->dontVisibleRecords(['Carlos', 'Andres'])
            ->seeder(fn() => throw new Exception('Faker exception caused by seed function called on init function'));

        $provider->init(false);
    }

    /** @dataProvider filters */
    public function test_filter_data($filter)
    {
        $this->assertFilterData(uri: '/filters', filter: $filter, filterable: fn ($data) => User::create($data));
    }

    public function filters(): array
    {
        return [
            'filter by name' => [
                Filter::make('name')
                    ->visibleRecords('LUIS')
                    ->dontVisibleRecords(['ANDRES', 'BEN', 'CARLOS'])
            ],

            'filter by state (without trashed)' => [
                Filter::make('state')
                    ->visibleRecords('Luis')
                    ->dontVisibleRecords(['Carlos', 'Juan'])
                    ->value('without')
                    ->field('deleted_at')
                    ->seeder(function () {
                        User::create(['name' => 'Luis']);
                        User::create(['name' => 'Carlos'])->delete();
                        User::create(['name' => 'Juan'])->delete();
                    })
            ],

            'filter by state (only trashed)' => [
                Filter::make('state')
                    ->visibleRecords('Carlos')
                    ->dontVisibleRecords(['Luis', 'Juan'])
                    ->value('only')
                    ->field('deleted_at')
                    ->seeder(function () {
                        User::create(['name' => 'Luis']);
                        User::create(['name' => 'Carlos'])->delete();
                        User::create(['name' => 'Juan']);
                    })
            ],
        ];
    }
}
