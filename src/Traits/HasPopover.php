<?php

namespace Xite\Wiretables\Traits;

use Closure;

trait HasPopover
{
    private ?Closure $popoverCallback = null;
    private ?string $popover = null;

    public function popover(callable|string $popover): self
    {
        if (is_callable($popover)) {
            $this->popoverCallback = $popover;
        }

        if (is_string($popover)) {
            $this->popover = $popover;
        }

        return $this;
    }

    public function getPopover($row): ?string
    {
        return ! is_null($this->popoverCallback)
            ? call_user_func($this->popoverCallback, $row)
            : $this->popover;
    }
}
