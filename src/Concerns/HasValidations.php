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

        return is_null($from)
            ? $this->{$method}($uri, $validation->data)
                ->assertInvalid($validation->error, $validation->bag)
            : $this->from($from)->{$method}($uri, $validation->data)
                ->assertInvalid($validation->error, $validation->bag);
    }
}
