<?php

namespace Luilliarcec\DevUtilities\DataProviders;

use Closure;

class Filter
{
    public function __construct(
        public string $filter,
        public mixed $see,
        public array $dontSee,
        public mixed $value = null,
        public ?string $field = null,
        public ?string $bag = null,
        protected mixed $seed = null
    ) {
        $this->field = $this->field ?: $this->filter;

        $this->value = $this->value ?: (is_string($this->see) ? $this->see : null);
    }

    public function init(mixed $filterable): void
    {
        if ($this->seed instanceof Closure) {
            $this->seed();
        } else {
            $this->data($filterable);
        }
    }

    protected function seed(): void
    {
        $callback = $this->seed;
        $callback();
    }

    protected function data(mixed $filterable): void
    {
        collect($this->dontSee)->each(function ($item) use ($filterable) {
            return $this->factory($filterable, $item);
        });

        $this->factory($filterable, $this->see);
    }

    protected function factory(mixed $filterable, mixed $value)
    {
        if (is_string($filterable)) {
            return $filterable::factory()->create([$this->field => $value]);
        }

        if ($filterable instanceof Closure) {
            return $filterable([$this->field => $value]);
        }

        return $filterable;
    }
}
