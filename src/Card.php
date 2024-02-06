<?php

namespace Xite\Wiretables;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Buttons\ModalButton;
use Xite\Wiretables\Contracts\ButtonContract;
use Xite\Wiretables\Contracts\TableContract;
use Xite\Wiretables\Traits\WithActions;
use Xite\Wiretables\Traits\WithButtons;

abstract class Card extends Wiretable implements TableContract
{
    use WithButtons;

    public bool $withHeader = false;
    public bool $withFooter = false;
    public bool $showPerPageOptions = false;
    public int $perPage = 10;

    public function render(): View
    {
        return view('wiretables::card')
            ->with(
                'buttons',
                $this->globalButtons()
                    ->map(fn ($button) => $button->renderIt()->render())
                    ->implode("\r\n")
            );
    }
}
