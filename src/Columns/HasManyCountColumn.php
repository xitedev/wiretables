<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Traits\HasColor;
use Xite\Wiretables\Traits\HasPopover;
use Xite\Wiretables\Traits\HasRoute;

class HasManyCountColumn extends Column
{
    use HasColor;
    use HasPopover;
    use HasRoute;

    public function canDisplay($row): bool
    {
        if (! parent::canDisplay($row)) {
            return false;
        }

        return (bool)$this->displayData($row);
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
                'popover' => $this->getPopover($row),
                'route' => $this->getRoute($row),
            ])
            ->render();
    }

    public function render(): View
    {
        return view('wiretables::columns.has-many-count-column');
    }
}
