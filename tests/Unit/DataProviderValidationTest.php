<?php

namespace Tests\Unit;

use Exception;
use Luilliarcec\DevUtilities\Concerns\HasValidations;
use Luilliarcec\DevUtilities\DataProviders\Validation;
use Tests\TestCase;
use Tests\Utils\User;

class DataProviderValidationTest extends TestCase
{
    use HasValidations;

    public function test_that_the_data_is_accessible()
    {
        $provider = Validation::make('first_name')
            ->value('Luis Arce')
            ->data(['key' => 'value'])
            ->errorMessage('This is a error message')
            ->bag('default');

        $this->assertEquals('first_name', $provider->getName());
        $this->assertEquals(
            [
                'key' => 'value',
                'first_name' => 'Luis Arce'
            ],
            $provider->getData()
        );
        $this->assertEquals('default', $provider->getBag());

        $provider = Validation::make('first_name')
            ->value(fn() => 'Chester');

        $provider->init();

        $this->assertEquals(
            ['first_name' => 'Chester'],
            $provider->getData()
        );
    }

    public function test_that_seed_is_executed_in_the_init_function()
    {
        $this->expectExceptionMessage('Faker exception caused by seed function called on init function');

        $provider = Validation::make('first_name')
            ->seeder(fn() => throw new Exception('Faker exception caused by seed function called on init function'));

        $provider->init();
    }

    public function test_that_the_data_merge_with_the_value_field_to_validate_in_the_init_function()
    {
        $provider = Validation::make('first_name')
            ->value('Luis')
            ->data(['key' => 'value']);

        $provider->init();

        $this->assertEquals(
            ['key' => 'value', 'first_name' => 'Luis'],
            $provider->getData()
        );
    }

    /** @dataProvider validations */
    public function test_validation_rules($validation)
    {
        $this->assertValidation(uri: '/form', validation: $validation);
    }

    public function validations(): array
    {
        return [
            'first_name is required' => [
                Validation::make('first_name'),
            ],
            'first_name is string' => [
                Validation::make('first_name')
                    ->value([]),
            ],
            'first_name is min:5' => [
                Validation::make('first_name')
                    ->value('Lui')
                    ->errorMessage('The first name must be at least 5 characters.'),
            ],
            'email is nullable' => [
                Validation::make('email')
                    ->value('Lui')
                    ->rule('nullable'),
            ],
            'email is unique' => [
                Validation::make('email')
                    ->value('luilliarcec@gmail.com')
                    ->seeder(fn() => User::create(['name' => 'Luis', 'email' => 'luilliarcec@gmail.com']))
                    ->errorMessage('The email has already been taken.'),
            ],
        ];
    }
}
