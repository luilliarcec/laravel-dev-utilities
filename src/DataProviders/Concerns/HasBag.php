<?php

namespace Luilliarcec\DevUtilities\DataProviders\Concerns;

trait HasBag
{
    public ?string $bag = null;

    public function bag(string $bag): static
    {
        $this->bag = $bag;

        return $this;
    }

    public function getBag(): ?string
    {
        return $this->bag;
    }
}
