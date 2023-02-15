<?php

namespace Xite\Wiretables\Columns;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\View\Component;
use Xite\Wireforms\Traits\Authorizable;
use Xite\Wireforms\Traits\Makeable;
use Xite\Wiretables\Contracts\ColumnContract;
use Xite\Wiretables\Traits\HasClass;

abstract class Column extends Component implements ColumnContract
{
    use Authorizable;
    use HasClass;
    use Conditionable;
    use Makeable;

    protected string $name;
    protected ?string $title = null;
    protected ?int $width = null;

    protected bool $sortable = false;
    protected ?string $sortableField = null;

    protected ?Closure $displayCallback = null;
    protected ?Closure $displayCondition = null;

    protected ?string $currentSort = null;
    protected ?string $highlight = null;
    public bool $hasHighlight = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    abstract public function render(): ?View;

    public function title(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function sortable(?string $field = null): self
    {
        $this->sortable = true;
        $this->sortableField = $field;

        return $this;
    }

    public function width(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function highlight(string $highlight): self
    {
        $this->highlight = $highlight;

        return $this;
    }

    public function displayUsing(callable $displayCallback): self
    {
        $this->displayCallback = $displayCallback;

        return $this;
    }

    public function displayIf(callable $displayCondition): self
    {
        $this->displayCondition = $displayCondition;

        return $this;
    }

    public function currentSort($sort): self
    {
        $this->currentSort = $sort;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function isSortable(): bool
    {
        return $this->sortable;
    }

    public function getSortableField(): string
    {
        return $this->sortableField ?? $this->name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWidth(): string
    {
        return $this->width ? $this->width."%" : 'auto';
    }

    protected function isHighlighting(?string $value = null): bool
    {
        return Str::of($value)
            ->lower()
            ->contains(
                Str::of($this->highlight)->lower()
            );
    }

    public function canDisplay($row): bool
    {
        return ! is_callable($this->displayCondition) || (bool)call_user_func($this->displayCondition, $row);
    }

    public function hasDisplayCallback(): bool
    {
        return is_callable($this->displayCallback);
    }

    public function display($row)
    {
        return call_user_func($this->displayCallback, $row);
    }

    public function getSlotName(): string
    {
        return sprintf('column_%s', $this->getName());
    }

    protected function getValue($row)
    {
        return data_get($row->toArray(), $this->getName());
    }

    protected function displayData($row)
    {
        return once(
            fn () => $this->hasDisplayCallback()
                ? $this->display($row)
                : $this->getValue($row)
        );
    }

    public function renderTitle(): ?string
    {
        if (is_null($this->currentSort) || ! $this->isSortable()) {
            return $this->getTitle();
        }

        return view('wiretables::partials.table-title')
            ->with([
                'name' => $this->getSortableField(),
                'title' => $this->getTitle(),
                'isCurrentSort' => Str::of($this->currentSort)
                    ->replaceFirst('-', '')
                    ->is($this->getSortableField()),
                'isSortUp' => $this->currentSort === $this->getSortableField(),
                'sort' => $this->currentSort,
            ])
            ->render();
    }

    public function renderIt($row): ?string
    {
        if ($this->hasDisplayCallback()) {
            return $this->display($row);
        }

        if (is_null($this->render())) {
            return $this->getValue($row);
        }

        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $this->getValue($row),
            ])
            ->render();
    }
}
