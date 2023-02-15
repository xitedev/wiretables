<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class _SortableColumn extends Column
{
    public function render(): View
    {
        return view('wiretables::columns.sortable-column');
    }
}
