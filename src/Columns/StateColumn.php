<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Traits\HasFilterable;
use Xite\Wiretables\Traits\HasPopover;

class StateColumn extends Column
{
    use HasFilterable;
    use HasPopover;

    private bool $paintOverText = false;
    private bool $showText = true;

    public function paintOverText(): self
    {
        $this->paintOverText = true;

        return $this;
    }

    public function hideText(): self
    {
        $this->showText = false;

        return $this;
    }

    public function renderIt($row): ?string
    {
        if ($this->hasDisplayCallback()) {
            return $this->display($row);
        }

        if (is_null($this->render())) {
            return $this->getValue($row);
        }

        $filter = $this->getFilterableField($row);
        $data = $this->displayData($row);

        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $data,
                'popover' => $this->getPopover($row),
                'paintOverText' => $this->paintOverText,
                'showText' => $this->showText,
                'filter' => $filter,
                'filterValue' => $this->getName() !== $filter
                    ? $row->{$filter}
                    : $data?->getValue(),
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.state-column');
    }
}
