<?php

namespace Luilliarcec\DevUtilities\DataProviders;

use Illuminate\Support\Str;

class Sorter
{
    use Concerns\HasName;
    use Concerns\HasField;
    use Concerns\HasSeed;
    use Concerns\HasOrder;
    use Concerns\HasFactory;

    public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function init(mixed $sortable): void
    {
        if (!$this->seed()) {
            $this->factoryRecords($sortable);
        }
    }

    protected function factoryRecords(mixed $sortable): void
    {
        collect($this->getOrderedRecords())
            ->shuffle()
            ->each(fn ($item) => $this->factory($sortable, $item));
    }

    public function getField(): string
    {
        return $this->field ?? Str::replaceFirst('-', '', $this->getName());
    }
}
