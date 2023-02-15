<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class ButtonColumn extends Column
{
    public string $style = 'primary';
    public ?string $modal = null;
    public bool $outline = false;

    public function style(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function modal(string $modal): self
    {
        $this->modal = $modal;

        return $this;
    }

    public function outline(): self
    {
        $this->outline = true;

        return $this;
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'modal' => $this->modal,
                'style' => $this->style,
                'outline' => $this->outline,
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.button-column');
    }
}
