<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class SortableColumn extends Column
{
    protected ?int $width = 1;
    protected bool $sortable = true;

    public function render(): View
    {
        return view('wiretables::columns.sortable-column');
    }
}
