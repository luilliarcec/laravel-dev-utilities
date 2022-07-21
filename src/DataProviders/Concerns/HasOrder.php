<?php

namespace Luilliarcec\DevUtilities\DataProviders\Concerns;

trait HasOrder
{
    protected array $orderedRecords = [];
    protected string $orden = 'asc';

    public function asc(): static
    {
        $this->orden = 'asc';

        return $this;
    }

    public function desc(): static
    {
        $this->orden = 'desc';

        return $this;
    }

    public function getOrden(): string
    {
        return $this->orden;
    }

    public function orderedRecords(array $orderedRecords, string $order = 'asc'): static
    {
        $this->orderedRecords = $orderedRecords;
        $this->orden = $order;

        return $this;
    }

    public function getOrderedRecords(): array
    {
        return $this->orderedRecords;
    }
}
