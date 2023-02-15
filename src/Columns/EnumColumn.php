<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class EnumColumn extends Column
{
    protected function getValue($row)
    {
        return $row->{$this->getName()};
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $this->displayData($row),
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.enum-column');
    }
}
