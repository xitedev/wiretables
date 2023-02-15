<?php

namespace Xite\Wiretables\Filters;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Xite\Wireforms\Components\Fields\NestedSetSelect;
use Xite\Wireforms\Components\Fields\WireSelect;

class WireSelectFilter extends Filter
{
    private ?string $model = null;
    protected bool $nestedSet = false;
    protected bool $searchable = false;
    protected ?int $minInputLength = null;
    protected ?string $orderBy = null;
    protected ?string $orderDir = null;
    protected ?string $keyBy = null;
    protected ?Collection $filters = null;

    public function model(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function nestedSet(): self
    {
        $this->nestedSet = true;

        return $this;
    }

    public function searchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    public function minInputLength(int $minInputLength): self
    {
        $this->minInputLength = $minInputLength;

        return $this;
    }

    public function orderBy(string $orderBy): self
    {
        $this->orderBy = $orderBy;

        return $this;
    }

    public function orderByDesc(string $orderBy): self
    {
        $this->orderBy($orderBy);
        $this->orderDir = 'desc';

        return $this;
    }

    public function keyBy(string $keyBy): self
    {
        $this->keyBy = $keyBy;

        return $this;
    }

    public function filterBy(string $key, $value): self
    {
        if (is_null($value)) {
            return $this;
        }

        if (! $this->filters) {
            $this->filters = Collection::make();
        }

        $this->filters->put($key, $value);

        return $this;
    }

    public function render(): View
    {
        $class = ($this->nestedSet)
            ? NestedSetSelect::class
            : WireSelect::class;

        return $class::make(
            name: $this->getName(),
            placeholder: $this->getPlaceholder(),
            showLabel: false,
            value: $this->getValue($this->value),
            model: $this->model,
            searchable: $this->searchable,
            minInputLength: $this->minInputLength,
            orderBy: $this->orderBy,
            orderDir: $this->orderDir,
            filters: $this->filters,
            key: $this->keyBy,
            emitUp: 'addFilter'
        )
            ->withAttributes([
                "x-on:update-{$this->getKebabName()}.window" => "event => { \$el.querySelectorAll('div[wire\\\\:id]').forEach((el) => window.Livewire.find(el.getAttribute('wire:id')).emitSelf('fillParent', event.detail.value, event.detail.trigger)) }",
            ])
            ->render();
    }
}
