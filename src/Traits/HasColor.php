<?php

namespace Xite\Wiretables\Traits;

use Closure;

trait HasColor
{
    private ?Closure $colorCallback = null;
    private string $color = 'bg-primary-100 text-primary-700';

    public function color(string|callable $color): self
    {
        if (is_callable($color)) {
            $this->colorCallback = $color;
        }

        if (is_string($color)) {
            $this->color = $color;
        }

        return $this;
    }

    public function getColor($row): string
    {
        return ! is_null($this->colorCallback)
            ? call_user_func($this->colorCallback, $row)
            : $this->color;
    }
}
