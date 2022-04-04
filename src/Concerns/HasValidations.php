<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Testing\TestResponse;
use Luilliarcec\DevUtilities\DataProviders\Validation;

trait HasValidations
{
    public function assertValidation(
        string $uri,
        Validation $validation,
        string $method = 'post',
        string|null $from = null,
        array $data = []
    ): TestResponse {
        $validation->init($data);

        $request = is_null($from)
            ? $this->{$method}($uri, $validation->data)
            : $this->from($from)->{$method}($uri, $validation->data);

        return $validation->isValid
            ? $request->assertValid($validation->field, $validation->bag)
            : $request->assertInvalid($validation->error, $validation->bag);
    }
}
