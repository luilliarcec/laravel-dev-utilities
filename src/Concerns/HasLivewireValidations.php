<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Luilliarcec\DevUtilities\DataProviders\Validations;

trait HasLivewireValidations
{
    public function assertValidation(Validations $validation, mixed $component, ?string $method = null): mixed
    {
        $validation->init();

        foreach ($validation->data as $key => $value) {
            $component->set($key, $value);
        }

        if ($method) {
            $component->call($method);
        }

        return $component
            ->assertHasErrors($validation->error);
    }
}
