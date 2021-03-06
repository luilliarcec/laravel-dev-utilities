<?php

namespace Luilliarcec\DevUtilities\DataProviders;

use Closure;

class Filter
{
    use Concerns\HasName;
    use Concerns\HasValue;
    use Concerns\HasBag;
    use Concerns\HasSeed;
    use Concerns\HasField;
    use Concerns\HasRecords;
    use Concerns\HasFactory;

    public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function init(mixed $filterable): void
    {
        if (! $this->seed()) {
            $this->factoryRecords($filterable);
        }
    }

    protected function factoryRecords(mixed $filterable): void
    {
        collect($this->getDontVisibleRecords())
            ->each(fn($item) => $this->factory($filterable, $item));

        $this->factory($filterable, $this->getVisibleRecords());
    }

    public function getValue(): mixed
    {
        return $this->value ?? (is_string($record = $this->getVisibleRecords()) ? $record : null);
    }
}
