<?php

namespace Luilliarcec\DevUtilities\Concerns;

use Illuminate\Testing\TestResponse;
use Luilliarcec\DevUtilities\DataProviders\Fields;

trait HasFields
{
    public function assertShowField(string $uri, Fields $field, mixed $toggleable): TestResponse
    {
        $field->init($toggleable);

        return $this->get("$uri?$field->parameters")
            ->assertSeeText($field->see)
            ->assertDontSeeText($field->dontSee);
    }
}
