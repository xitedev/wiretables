<?php

namespace Xite\Wiretables\Traits;

trait HasFilterable
{
    private bool $filterable = false;
    private ?\Closure $filterableCondition = null;
    private ?string $filterableField = null;

    public function filterable(string $field = null): self
    {
        $this->filterable = true;
        $this->filterableField = $field;

        return $this;
    }

    public function notFilterable(): self
    {
        $this->filterable = false;
        $this->filterableField = null;

        return $this;
    }

    public function filterableUsing(callable $condition): self
    {
        $this->filterableCondition = $condition;

        return $this;
    }

    public function getFilterableField($row): ?string
    {
        if (is_callable($this->filterableCondition)) {
            return call_user_func($this->filterableCondition, $row);
        }

        if (! $this->filterable) {
            return null;
        }

        return $this->filterableField ?? $this->getName();
    }
}
