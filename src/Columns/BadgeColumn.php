<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Traits\HasColor;
use Xite\Wiretables\Traits\HasPopover;

class BadgeColumn extends Column
{
    use HasColor;
    use HasPopover;

    private bool $small = false;

    public function canDisplay($row): bool
    {
        if (! parent::canDisplay($row)) {
            return false;
        }

        return ! is_null($this->displayData($row));
    }

    public function smallBadge(): self
    {
        $this->small = true;

        return $this;
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $this->displayData($row),
                'color' => $this->getColor($row),
                'small' => $this->small,
                'popover' => $this->getPopover($row),
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.badge-column');
    }
}
