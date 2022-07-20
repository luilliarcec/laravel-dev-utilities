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
            ? $this->{$method}($uri, $validation->getData())
            : $this->from($from)->{$method}($uri, $validation->getData());

        return $validation->isAnErrorExcludedRule()
            ? $request->assertValid($validation->getName(), $validation->getBag())
            : $request->assertInvalid($validation->getError(), $validation->getBag());
    }
}
