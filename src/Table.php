<?php

namespace Xite\Wiretables;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Contracts\TableContract;
use Xite\Wiretables\Traits\WithActions;
use Xite\Wiretables\Traits\WithButtons;
use Xite\Wiretables\Traits\WithFiltering;
use Xite\Wiretables\Traits\WithSearching;
use Xite\Wiretables\Traits\WithSorting;

abstract class Table extends Wiretable implements TableContract
{
    use WithFiltering;
    use WithSearching;
    use WithButtons;
    use WithActions;
    use WithSorting;

    public ?string $layout = null;

    public function render(): View
    {
        return view('wiretables::table')
            ->layout($this->layout ?? config('wiretables.layout'))
            ->title($this->title)
            ->layoutData([
                'buttons' =>  $this->globalButtons()
                    ->map(fn ($button) => $button->renderIt()->render())
                    ->implode("\r\n")
            ]);
    }
}
