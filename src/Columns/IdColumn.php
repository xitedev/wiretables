<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class IdColumn extends Column
{
    public bool $hasHighlight = true;
    public array $class = ['pl-2'];
    public ?string $showRoute = null;

    public function getTitle(): ?string
    {
        return __('wiretables::table.id');
    }

    public function showRoute(string $showRoute): self
    {
        $this->showRoute = $showRoute;

        return $this;
    }

    public function isSortable(): bool
    {
        return true;
    }

    public function render(): ?View
    {
        if ($this->showRoute) {
            return view('wiretables::columns.id-column', [
                'showRoute' => $this->showRoute,
            ]);
        }

        return null;
    }
}
