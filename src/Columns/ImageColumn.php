<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Traits\HasClass;

class ImageColumn extends Column
{
    private ?string $thumbnail = null;
    private ?string $innerClass = null;

    public function thumbnail(string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function innerClass(string $innerClass): self
    {
        $this->innerClass = $innerClass;

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

        if (!$this->displayCallback && ! method_exists($row, 'getFirstMediaUrl')) {
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
                'innerClass' => $this->innerClass,
            ])
            ->render();
    }

    public function render(): View
    {
        return view('wiretables::columns.image-column');
    }
}
