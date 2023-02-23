<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class BooleanColumn extends Column
{
    protected ?int $width = 1;

    public function render(): View
    {
        return view('wiretables::columns.boolean-column');
    }
}
