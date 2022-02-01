<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Testing\TestResponse;
use Luilliarcec\DevUtilities\DataProviders\Validations;

trait HasLivewireValidations
{
    public function assertValidation(Validations $validation, string $method, mixed $component): mixed
    {
        $validation->init();

        return $component
            ->set($validation->field, $validation->value)
            ->call($method)
            ->assertHasErrors([$validation->field => $validation->rule]);
    }
}
