<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Testing\TestResponse;
use Luilliarcec\DevUtilities\DataProviders\Validations;

trait HasValidations
{
    public function assertValidation(
        string $uri,
        Validations $validation,
        string $method = 'post',
        string|null $from = null,
        array $data = []
    ): TestResponse {
        $validation->init($data);

        $request = is_null($from)
            ? $this->{$method}($uri, $validation->data)
            : $this->from($from)->{$method}($uri, $validation->data);

        if ($validation->isValid) {
            return $request->assertValid($validation->field, $validation->bag);
        }

        return $request->assertInvalid($validation->error, $validation->bag);
    }
}
