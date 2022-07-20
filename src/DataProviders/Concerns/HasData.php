<?php

namespace Luilliarcec\DevUtilities\DataProviders\Concerns;

trait HasData
{
    protected array $data = [];

    public function data(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
