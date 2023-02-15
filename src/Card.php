<?php

namespace Xite\Wiretables;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Buttons\ModalButton;
use Xite\Wiretables\Contracts\ButtonContract;
use Xite\Wiretables\Traits\WithActions;
use Xite\Wiretables\Traits\WithButtons;

abstract class Card extends Wiretable
{
    use WithButtons;
    use WithActions;

    public bool $withHeader = false;
    public bool $withFooter = false;
    public int $perPage = 10;

    protected function getCreateButton(): ButtonContract
    {
        return ModalButton::make('create')
            ->icon('heroicon-o-plus-circle')
            ->modal($this->createButton)
            ->class('px-4 h-full !rounded-none')
            ->withParams(fn () => $this->getCreateButtonParams())
            ->displayIf(fn () => $this->can('create', $this->model));
    }

    public function getTotalProperty(): ?string
    {
        return null;
    }

    public function render(): View
    {
        return view('wiretables::card');
    }
}
