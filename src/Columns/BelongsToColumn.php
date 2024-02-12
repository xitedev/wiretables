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
    private ?\Closure $copyString = null;

    public function copyButton(?callable $string = null): self
    {
        $this->copyButton = true;
        $this->copyString = $string;

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

    private function getCopyButton($row, $data): ?string
    {
        if (! $this->copyButton) {
            return null;
        }

        if (! $this->copyString) {
            return $data;
        }

        return call_user_func($this->copyString, $row);
    }

    public function renderIt($row): ?string
    {
        $filter = $this->getFilterableField($row);
        $value = $this->getValue($row)?->getKey();
        $data = $this->displayMapping($row);

        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $data,
                'value' => $value,
                'filter' => $filter,
                'filterValue' => $this->getName() !== $filter
                    ? $row->{$filter}
                    : $value,
                'icon' => $this->getIcon(),
                'showModal' => $this->showModal,
                'route' => $this->getRoute($row),
                'copyButton' => $this->getCopyButton($row, $data),
            ])
            ->render();
    }

    public function render(): View
    {
        return view('wiretables::columns.belongs-to-column');
    }
}
