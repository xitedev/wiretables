<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Xite\Wiretables\Traits\HasFilterable;
use Xite\Wiretables\Traits\HasIcon;
use Xite\Wiretables\Traits\HasRoute;

class BelongsToColumn extends Column
{
    use HasFilterable;
    use HasIcon;
    use HasRoute;

    protected ?string $showModal = null;
    private bool $copyButton = false;

    public function copyButton(): self
    {
        $this->copyButton = true;

        return $this;
    }

    protected function getValue($row)
    {
        return once(function () use ($row) {
            $string = Str::of($this->getName());

            if ($string->contains('.')) {
                [$parent, $child] = $string->explode('.', 2);

                return $row->{$parent}?->{$child};
            }

            return $row->{$this->getName()};
        });
    }

    public function canDisplay($row): bool
    {
        if (! parent::canDisplay($row)) {
            return false;
        }

        return ! is_null($this->displayData($row));
    }

    public function showModal(string $showModal): self
    {
        $this->showModal = $showModal;

        return $this;
    }

    private function displayMapping($row): ?string
    {
        $value = $this->displayData($row);

        if (! is_object($value)) {
            return $value;
        }

        return method_exists($value, 'getDisplayName')
            ? $value->getDisplayName()
            : $value->getKey();
    }

    public function renderIt($row): ?string
    {
        $filter = $this->getFilterableField($row);
        $value = $this->getValue($row)?->getKey();

        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $this->displayMapping($row),
                'value' => $value,
                'filter' => $filter,
                'filterValue' => $this->getName() !== $filter
                    ? $row->{$filter}
                    : $value,
                'icon' => $this->getIcon(),
                'showModal' => $this->showModal,
                'route' => $this->getRoute($row),
                'copyButton' => $this->copyButton,
            ])
            ->render();
    }

    public function render(): View
    {
        return view('wiretables::columns.belongs-to-column');
    }
}
