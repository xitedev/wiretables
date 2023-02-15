<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class ImageColumn extends Column
{
    public ?string $thumbnail = null;

    public function thumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    protected function getValue($row)
    {
        return ! is_null($this->thumbnail)
            ? $row->getFirstMediaUrl($this->getName(), $this->thumbnail)
            : $row->getFirstMediaUrl($this->getName());
    }

    public function canDisplay($row): bool
    {
        if (! parent::canDisplay($row)) {
            return false;
        }

        if (! method_exists($row, 'getFirstMediaUrl')) {
            return false;
        }

        return ! is_null($this->displayData($row));
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $this->displayData($row),
                'alt' => $row->getDisplayName(),
            ])
            ->render();
    }

    public function render(): View
    {
        return view('wiretables::columns.image-column');
    }
}
