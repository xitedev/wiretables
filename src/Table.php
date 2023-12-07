<?php

namespace Xite\Wiretables;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Traits\WithActions;
use Xite\Wiretables\Traits\WithButtons;
use Xite\Wiretables\Traits\WithFiltering;
use Xite\Wiretables\Traits\WithSearching;
use Xite\Wiretables\Traits\WithSorting;

abstract class Table extends Wiretable
{
    use WithFiltering;
    use WithSorting;
    use WithSearching;
    use WithButtons;
    use WithActions;

    public ?string $layout = null;

    public function render(): View
    {
        return view('wiretables::table')
            ->layout($this->layout ?? config('wiretables.layout'))
            ->title($this->title)
            ->layoutData([
                'buttons' =>  $this->globalButtons
                    ->map(fn ($button) => $button->renderIt()->render())
                    ->implode("\r\n")
            ]);
    }
}
