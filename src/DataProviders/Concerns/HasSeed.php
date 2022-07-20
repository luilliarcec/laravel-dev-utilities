<?php

namespace Luilliarcec\DevUtilities\DataProviders\Concerns;

use Closure;

trait HasSeed
{
    protected Closure|null $seed = null;

    public function seeder(Closure $seed): static
    {
        $this->seed = $seed;

        return $this;
    }

    protected function seed(array $data = []): void
    {
        if ($this->seed instanceof Closure) {
            $callback = $this->seed;
            $callback($data);
        }
    }
}
