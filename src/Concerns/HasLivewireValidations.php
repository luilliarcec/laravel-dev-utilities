<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Luilliarcec\DevUtilities\DataProviders\Validation;

trait HasLivewireValidations
{
    public function assertValidation(Validation $validation, mixed $component, ?string $method = null): mixed
    {
        $validation->init();

        foreach ($validation->data as $key => $value) {
            $component->set($key, $value);
        }

        if ($method) {
            $component->call($method);
        }

        return $validation->isValid
            ? $component->assertHasNoErrors($validation->field)
            : $component->assertHasErrors($validation->error);
    }
}
