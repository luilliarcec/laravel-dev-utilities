<?php

namespace Tests\Unit;

use Exception;
use Luilliarcec\DevUtilities\Concerns\HasValidations;
use Luilliarcec\DevUtilities\DataProviders\Validations;
use Tests\TestCase;
use Tests\Utils\User;

class DataProviderValidationTest extends TestCase
{
    use HasValidations;

    public function test_that_the_data_is_accessible()
    {
        $provider = new Validations(
            field: 'first_name', value: 'Luis Arce',
            data: ['key' => 'value'],
            message: 'This is a error message', bag: 'default'
        );

        $this->assertEquals('first_name', $provider->field);
        $this->assertEquals(['key' => 'value'], $provider->data);
        $this->assertEquals('default', $provider->bag);

        $provider = new Validations(field: 'first_name', value: fn () => 'Chester');

        $provider->init();

        $this->assertEquals(
            ['first_name' => 'Chester'],
            $provider->data
        );
    }

    public function test_that_seed_is_executed_in_the_init_function()
    {
        $this->expectExceptionMessage('Faker exception caused by seed function called on init function');

        $provider = new Validations(
            field: 'first_name',
            seed: fn () => throw new Exception('Faker exception caused by seed function called on init function')
        );

        $provider->init();
    }

    public function test_that_the_data_merge_with_the_value_field_to_validate_in_the_init_function()
    {
        $provider = new Validations(field: 'first_name', value: 'Luis', data: ['key' => 'value']);

        $provider->init();

        $this->assertEquals(
            ['key' => 'value', 'first_name' => 'Luis'],
            $provider->data
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
                new Validations(
                    field: 'first_name'
                ),
            ],
            'first_name is string' => [
                new Validations(
                    field: 'first_name',
                    value: []
                ),
            ],
            'first_name is min:5' => [
                new Validations(
                    field: 'first_name',
                    value: 'Lui',
                    message: 'The first name must be at least 5 characters.'
                ),
            ],
            'email is nullable' => [
                new Validations(
                    field: 'email',
                    isValid: true
                ),
            ],
            'email is unique' => [
                new Validations(
                    field: 'email',
                    value: 'luilliarcec@gmail.com',
                    seed: fn () => User::create(['name' => 'Luis', 'email' => 'luilliarcec@gmail.com']),
                    message: 'The email has already been taken.'
                ),
            ],
        ];
    }
}
