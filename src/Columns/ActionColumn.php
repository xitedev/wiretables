<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Xite\Wiretables\Contracts\ButtonContract;

class ActionColumn extends Column
{
    protected ?int $width = 1;
    private Collection $buttons;

    public function withButtons(Collection $buttons): self
    {
        $this->buttons = $buttons;

        return $this;
    }

    public function renderIt($row): ?string
    {
        $buttons = $this->buttons->filter(
            fn (ButtonContract $button) => $button->canDisplay($row)
        );

        if (!$buttons->count()) {
            return null;
        }

        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'row' => $row,
                'buttons' => $buttons,
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.action-column');
    }
}
