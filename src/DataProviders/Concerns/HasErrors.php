<?php

namespace Luilliarcec\DevUtilities\DataProviders\Concerns;

trait HasErrors
{
    protected string|null $errorKey = null;
    protected string|null $errorMessage = null;
    protected string|null $rule = null;
    protected array $errorExcludedRules = ['nullable'];

    public function errorKey(string $key): static
    {
        $this->errorKey = $key;

        return $this;
    }

    public function getErrorKey(): string
    {
        return $this->errorKey ?: $this->getName();
    }

    public function errorMessage(string $message): static
    {
        $this->errorMessage = $message;

        return $this;
    }

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function rule(string $rule): static
    {
        $this->rule = $rule;

        return $this;
    }

    public function getRule(): ?string
    {
        return $this->rule;
    }

    public function errorExcludedRules(array $excluded): static
    {
        $this->errorExcludedRules = $excluded;

        return $this;
    }

    public function getErrorExcludedRules(): array
    {
        return $this->errorExcludedRules;
    }

    public function isAnErrorExcludedRule(): bool
    {
        return in_array($this->getRule(), $this->getErrorExcludedRules());
    }

    public function getError(): array|string
    {
        $error = $this->getErrorKey();

        if ($message = $this->getErrorMessage()) {
            return [
                $error => $message
            ];
        }

        if ($rule = $this->getRule()) {
            return [
                $error => $rule,
            ];
        }

        return $error;
    }
}
