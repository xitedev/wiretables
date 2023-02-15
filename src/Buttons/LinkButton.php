<?php

namespace Xite\Wiretables\Buttons;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;

class LinkButton extends Button
{
    private string $target = '_self';

    public function target(string $target): self
    {
        $this->target = Str::of($target)
            ->unless(
                fn ($string) => $string->startsWith('_'),
                fn ($string) => $string->prepend('_')
            )
            ->toString();

        return $this;
    }

    public function render(): View
    {
        return view('wiretables::buttons.link-button', [
            'target' => $this->target,
        ]);
    }
}
