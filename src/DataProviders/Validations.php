<?php

namespace Luilliarcec\DevUtilities\DataProviders;

class Validations
{
    public string|array $error;

    public function __construct(
        public string         $field,
        protected mixed       $value = null,
        public array          $data = [],
        protected mixed       $seed = null,
        protected string|null $errorKey = null,
        protected string|null $rule = null,
        protected string|null $message = null,
        public string         $bag = 'default',
    )
    {
        $this->errorKey = $this->errorKey ?: $this->field;
    }

    public function init(array $data = [])
    {
        $this->seed($data);
        $this->data();
        $this->error();
    }

    protected function seed(array $data)
    {
        if (is_callable($this->seed)) {
            $callback = $this->seed;
            $callback($data);
        }
    }

    protected function data()
    {
        $value = $this->value;

        $this->data = array_merge($this->data, [
            $this->field => is_callable($value)
                ? $value()
                : $value
        ]);
    }

    protected function error()
    {
        $this->error = $this->errorKey;

        if ($this->message) {
            $this->error = [
                $this->errorKey => $this->message
            ];
        } elseif ($this->rule) {
            $this->error = [
                $this->errorKey => $this->rule
            ];
        }
    }
}
