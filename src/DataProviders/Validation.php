<?php

namespace Luilliarcec\DevUtilities\DataProviders;

use Closure;

class Validation
{
    use Concerns\HasName;
    use Concerns\HasValue;
    use Concerns\HasData;
    use Concerns\HasSeed;
    use Concerns\HasErrors;
    use Concerns\HasBag;

    public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function make(string $name): static
    {
        return new static($name);
    }

    public function init(array $data = []): void
    {
        $this->seed($data);
    }

    public function getData(): array
    {
        $value = $this->getValue();

        return array_merge($this->data, [
            $this->getName() => $value instanceof Closure
                ? $value()
                : $value,
        ]);
    }
}
