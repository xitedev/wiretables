<?php

namespace Xite\Wiretables\Filters;

use Illuminate\Contracts\View\View;
use Xite\Wireforms\Components\Fields\Text;

class TextFilter extends Filter
{
    public function render(): View
    {
        return Text::make(
            name: $this->getName(),
            placeholder: $this->getPlaceholder(),
            showLabel: false,
            allowClear: true,
            value: $this->getValue($this->value)
        )
            ->withAttributes([
                "x-on:input.debounce.1s" => "\$wire.addFilter('$this->name', \$event.target.value)",
                "x-on:update-{$this->getKebabName()}.window" => "\$refs.input.value = event.detail.value",
            ])
            ->render();
    }
}
