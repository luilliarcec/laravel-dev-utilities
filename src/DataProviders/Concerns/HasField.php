<?php

namespace Luilliarcec\DevUtilities\DataProviders\Concerns;

trait HasField
{
    protected ?string $field = null;

    public function field(string $field): static
    {
        $this->field = $field;

        return $this;
    }

    public function getField(): string
    {
        return $this->field ?? $this->getName();
    }
}
