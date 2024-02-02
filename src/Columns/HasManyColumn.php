<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Traits\HasColor;
use Xite\Wiretables\Traits\HasFilterable;
use Xite\Wiretables\Traits\HasRoute;
use Closure;

class HasManyColumn extends Column
{
    use HasFilterable;
    use HasRoute;
    use HasColor;

    private ?Closure $tooltipCallback = null;
    private ?string $tooltipString = null;

    protected int $limit = 10;
    protected ?string $showRouteString = null;

    public function showUsing(string $showRoute): self
    {
        $this->showRouteString = $showRoute;

        return $this;
    }

    public function tooltipUsing(callable|string $tooltip) {
        if (is_callable($tooltip)) {
            $this->tooltipCallback = $tooltip;
        }

        if (is_string($tooltip)) {
            $this->tooltipString = $tooltip;
        }

        return $this;
    }

    public function getTooltips($row)
    {
        if (! is_null($this->tooltipCallback)) {
            return call_user_func($this->tooltipCallback, $row);
        }

        return $this->tooltipString;
    }

    public function getValue($row)
    {
        return $row->{$this->getName()};
    }

    protected function displayData($row)
    {
        return once(
            fn () => $this->hasDisplayCallback()
                ? $this->display($row)
                : $this->getValue($row)->mapWithKeys(
                    fn ($item) => [
                        $item->getKey() => $item->getDisplayName(),
                    ]
                )
        );
    }

    public function canDisplay($row): bool
    {
        if (! parent::canDisplay($row)) {
            return false;
        }

        return ! is_null($this->displayData($row));
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $this->displayData($row),
                'showRoute' => $this->showRouteString,
                'showMoreRoute' => $this->getRoute($row),
                'color' => $this->getColor($row),
                'limit' => $this->limit,
                'filter' => $this->getFilterableField($row),
                'tooltips' => $this->getTooltips($row)
            ])
            ->render();
    }

    public function render(): View
    {
        return view('wiretables::columns.has-many-column');
    }
}
