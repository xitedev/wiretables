<?php

namespace Xite\Wiretables\Filters;

use Illuminate\Contracts\View\View;
use Xite\Wireforms\Components\Fields\Select;

class SelectFilter extends Filter
{
    private array $options = [];
    private bool $nullable = false;
    protected ?string $keyBy = null;

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function nullable(): self
    {
        $this->nullable = true;

        return $this;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function keyBy(string $keyBy): self
    {
        $this->keyBy = $keyBy;

        return $this;
    }

//    TODO: add multiple property
    public function render(): View
    {
        return Select::make(
            name: $this->getName(),
            placeholder: $this->getPlaceholder(),
            showLabel: false,
            value: $this->getValue($this->value),
            options: $this->getOptions(),
            nullable: $this->nullable,
            key: $this->keyBy,
            emitUp: 'addFilter'
        )
            ->withAttributes([
                "x-on:update-{$this->getKebabName()}.window" => "event => { \$el.querySelectorAll('div[wire\\\\:id]').forEach((el) => window.Livewire.find(el.getAttribute('wire:id')).emitSelf('fillParent', event.detail.value, event.detail.trigger)) }",
            ])
            ->render();
    }
}
