<?php

namespace Luilliarcec\DevUtilities\DataProviders;

class Filters
{
    protected string $prefix = 'filter';

    public function __construct(
        protected string $filter,
        public mixed $see,
        public array $dontSee,
        public mixed $parameters = null,
        public ?string $field = null,
        public ?string $bag = null,
        protected mixed $seed = null
    ) {
        $this->field = $this->field ?: $this->filter;

        $this->parameters = $this->parameters ?: (is_string($this->see) ? $this->see : null);
        $this->parameters = $this->parameters();
    }

    public function init(mixed $filterable): void
    {
        if (is_callable($this->seed)) {
            $this->seed();
        } else {
            $this->data($filterable);
        }
    }

    protected function parameters(): string
    {
        return collect($this->parameters)
            ->transform(function ($item) {
                if (is_array($this->parameters)) {
                    return "$this->prefix[$this->filter][]=$item";
                }

                return "$this->prefix[$this->filter]=$item";
            })
            ->implode('&');
    }

    protected function seed()
    {
        $callback = $this->seed;
        $callback();
    }

    protected function data(mixed $filterable)
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

        if (is_callable($filterable)) {
            return $filterable([$this->field => $value]);
        }

        return $filterable;
    }
}
