<?php

namespace Xite\Wiretables\Traits;

use Closure;

trait HasClipboard
{
    private ?Closure $clipboardCallback = null;
    private ?string $clipboard = null;

    public function clipboard(callable|string $clipboard): self
    {
        if (is_callable($clipboard)) {
            $this->clipboardCallback = $clipboard;
        }

        if (is_string($clipboard)) {
            $this->clipboard = $clipboard;
        }

        return $this;
    }

    public function getClipboard($row): ?string
    {
        return ! is_null($this->clipboardCallback)
            ? call_user_func($this->clipboardCallback, $row)
            : $this->clipboard;
    }
}
