<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class PhoneColumn extends Column
{
    public bool $hasHighlight = true;
    private bool $asLink = false;
    private bool $copyButton = false;

    public function asLink(): self
    {
        $this->asLink = true;

        return $this;
    }

    public function copyButton(): self
    {
        $this->copyButton = true;

        return $this;
    }

    public function getValue($row)
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
                'asLink' => $this->asLink,
                'copyButton' => $this->copyButton,
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.phone-column');
    }
}
