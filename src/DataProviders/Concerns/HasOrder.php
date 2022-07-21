<?php

namespace Luilliarcec\DevUtilities\DataProviders\Concerns;

trait HasOrder
{
    protected array $orderedRecords = [];
    protected string $orderDirection = 'asc';

    public function asc(): static
    {
        $this->orderDirection = 'asc';

        return $this;
    }

    public function desc(): static
    {
        $this->orderDirection = 'desc';

        return $this;
    }

    public function getOrderDirection(): string
    {
        return $this->orderDirection;
    }

    public function orderedRecords(array $orderedRecords, string $direction = 'asc'): static
    {
        $this->orderedRecords = $orderedRecords;
        $this->orderDirection = $direction;

        return $this;
    }

    public function getOrderedRecords(): array
    {
        return $this->orderedRecords;
    }
}
