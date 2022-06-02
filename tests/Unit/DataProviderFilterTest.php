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
        $provider = new Filter(
            filter: 'name', see: 'Luis', dontSee: ['Carlos', 'Andres'], bag: 'data'
        );

        $this->assertEquals('name', $provider->field);
        $this->assertEquals('Luis', $provider->see);
        $this->assertEquals(['Carlos', 'Andres'], $provider->dontSee);
        $this->assertEquals('data', $provider->bag);
    }

    public function test_that_the_filter_is_used_as_a_field_if_the_field_is_not_sent()
    {
        $provider = new Filter(filter: 'name', see: 'Luis', dontSee: []);

        $this->assertEquals('name', $provider->field);

        $provider = new Filter(filter: 'name', see: 'Luis', dontSee: [], field: 'filter_name');

        $this->assertEquals('filter_name', $provider->field);
    }

    public function test_that_seed_is_executed_in_the_init_function()
    {
        $this->expectExceptionMessage('Faker exception caused by seed function called on init function');

        $provider = new Filter(
            filter: 'first_name', see: 'Luis', dontSee: ['Carlos', 'Andres'],
            seed  : fn () => throw new Exception('Faker exception caused by seed function called on init function')
        );

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
                new Filter(
                    filter : 'name',
                    see    : 'LUIS',
                    dontSee: ['ANDRES', 'BEN', 'CARLOS']
                ),
            ],

            'filter by state (without trashed)' => [
                new Filter(
                    filter : 'state',
                    see    : 'Luis',
                    dontSee: ['Carlos', 'Juan'],
                    value  : 'without',
                    field  : 'deleted_at',
                    seed   : function () {
                        User::create(['name' => 'Luis']);
                        User::create(['name' => 'Carlos'])->delete();
                        User::create(['name' => 'Juan'])->delete();
                    }
                ),
            ],

            'filter by state (only trashed)' => [
                new Filter(
                    filter : 'state',
                    see    : 'Carlos',
                    dontSee: ['Luis', 'Juan'],
                    value  : 'only',
                    field  : 'deleted_at',
                    seed   : function () {
                        User::create(['name' => 'Luis']);
                        User::create(['name' => 'Carlos'])->delete();
                        User::create(['name' => 'Juan']);
                    }
                ),
            ],
        ];
    }
}
