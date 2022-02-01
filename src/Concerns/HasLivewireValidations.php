<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Luilliarcec\DevUtilities\DataProviders\Validations;

trait HasLivewireValidations
{
    public function assertValidation(Validations $validation, string $method, mixed $component): mixed
    {
        $validation->init();

        foreach ($validation->data as $key => $value) {
            $component->set($key, $value);
        }

        return $component
            ->call($method)
            ->assertHasErrors($validation->error);
    }
}
