<?php

namespace Luilliarcec\DevUtilities\DataProviders\Concerns;

trait HasRecords
{
    protected string|array $visibleRecords = [];
    protected array $dontVisibleRecords = [];

    public function visibleRecords(string|array $records): static
    {
        $this->visibleRecords = $records;

        return $this;
    }

    public function getVisibleRecords(): string|array
    {
        return $this->visibleRecords;
    }

    public function dontVisibleRecords(array $records): static
    {
        $this->dontVisibleRecords = $records;

        return $this;
    }

    public function getDontVisibleRecords(): array
    {
        return $this->dontVisibleRecords;
    }
}
