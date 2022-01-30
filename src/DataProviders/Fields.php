<?php

namespace Luilliarcec\DevUtilities\DataProviders;

class Fields
{
    public string $parameters;

    public function __construct(
        public string $field,
        public array  $see,
        public array  $dontSee,
    )
    {
        $this->parameters = $this->parameters();
    }

    public function init(mixed $toggleable)
    {
        $this->factory($toggleable);
    }

    protected function parameters(): string
    {
        return "columns[]=$this->field";
    }

    protected function factory(mixed $toggleable)
    {
        if (is_string($toggleable)) {
            return $toggleable::factory()->create(
                array_merge($this->dontSee, $this->see)
            );
        }

        if (is_callable($toggleable)) {
            return $toggleable(
                array_merge($this->dontSee, $this->see)
            );
        }

        return $toggleable;
    }
}
