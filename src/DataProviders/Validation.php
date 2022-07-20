<?php

namespace Luilliarcec\DevUtilities\DataProviders;

use Closure;

class Validation
{
    public string|array $error;

    public function __construct(
        public string $field,
        protected mixed $value = null,
        public array $data = [],
        protected mixed $seed = null,
        protected string|null $errorKey = null,
        protected string|null $rule = null,
        protected string|null $message = null,
        public string $bag = 'default',
        public bool $isValid = false
    ) {
        $this->errorKey = $this->errorKey ?: $this->field;
    }

    public function init(array $data = []): void
    {
        $this->seed($data);
        $this->data();
        $this->error();
    }

    protected function seed(array $data): void
    {
        if ($this->seed instanceof Closure) {
            $callback = $this->seed;
            $callback($data);
        }
    }

    protected function data(): void
    {
        $value = $this->value;

        $this->data = array_merge($this->data, [
            $this->field => $value instanceof Closure
                ? $value()
                : $value,
        ]);
    }

    protected function error(): void
    {
        $this->error = $this->errorKey;

        if ($this->message) {
            $this->error = [
                $this->errorKey => $this->message,
            ];
        } elseif ($this->rule) {
            $this->error = [
                $this->errorKey => $this->rule,
            ];
        }
    }
}
