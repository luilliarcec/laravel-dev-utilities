<?php

namespace Luilliarcec\DevUtilities\DataProviders;

use Closure;
use Illuminate\Support\Str;

class Sorter
{
    public string $parameters;
    protected string $prefix = 'sort';

    public function __construct(
        public string $sort,
        public array $data,
        protected array $values = [],
        protected ?string $field = null,
        protected mixed $seed = null
    ) {
        $this->field = $this->field ?: Str::replaceFirst('-', '', $this->sort);
        $this->parameters = $this->parameters();
    }

    public function init(mixed $sortable): void
    {
        if ($this->seed instanceof Closure) {
            $this->seed();
        } else {
            $this->data($sortable);
        }
    }

    protected function parameters(): string
    {
        return "$this->prefix=$this->sort";
    }

    protected function seed(): void
    {
        $callback = $this->seed;
        $callback();
    }

    protected function data(mixed $sortable)
    {
        collect($this->values ?: $this->data)
            ->shuffle()
            ->each(fn ($item) => $this->factory($sortable, $item));
    }

    protected function factory(mixed $sortable, mixed $item)
    {
        if (is_string($sortable)) {
            return $sortable::factory()->create([$this->field => $item]);
        }

        if ($sortable instanceof Closure) {
            return $sortable([$this->field => $item]);
        }

        return $sortable;
    }
}
