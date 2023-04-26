<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class LinkColumn extends Column
{
    public string $target = '_blank';
    public ?string $label = null;
    private ?Closure $labelCallback = null;

    public function target(string $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function label(string|callable $label): self
    {
        if (is_callable($label)) {
            $this->labelCallback = $label;
        }

        if (is_string($label)) {
            $this->label = $label;
        }

        return $this;
    }

    private function getLabel($row): string
    {
        if (! is_null($this->labelCallback)) {
            return call_user_func($this->labelCallback, $row);
        }

        if (! is_null($this->label)) {
            return $this->label;
        }

        return $this->displayData($row);
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $this->displayData($row),
                'label' => $this->getLabel($row)
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.link-column');
    }
}
