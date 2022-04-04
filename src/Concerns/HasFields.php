<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Testing\TestResponse;
use Luilliarcec\DevUtilities\DataProviders\Field;

trait HasFields
{
    public function assertShowField(string $uri, Field $field, mixed $toggleable): TestResponse
    {
        $field->init($toggleable);

        return $this->get("$uri?$field->parameters")
            ->assertSeeText($field->see)
            ->assertDontSeeText($field->dontSee);
    }
}
