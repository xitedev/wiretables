<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Xite\Wiretables\Contracts\ColumnContract;

class StackColumn extends Column
{
    public bool $hasHighlight = true;
    private array $columns = [];
    public bool $asRow = false;
    public bool $toCenter = false;

    public function asRow(): self
    {
        $this->asRow = true;

        return $this;
    }

    public function toCenter(): self
    {
        $this->toCenter = true;

        return $this;
    }

    public function columns(array $columns): self
    {
        $this->columns = $columns;

        return $this;
    }

    private function getColumns(): Collection
    {
        return collect($this->columns)
            ->filter(fn ($column) => $column instanceof ColumnContract)
            ->filter(fn ($column) => $column->canRender)
            ->when(
                ! is_null($this->highlight),
                fn (Collection $columns) => $columns->each(
                    fn (Column $column) => $column->hasHighlight && $column->highlight($this->highlight)
                )
            );
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'row' => $row,
                'asRow' => $this->asRow,
                'toCenter' => $this->toCenter,
                'columns' => $this->getColumns()
                    ->filter(fn (ColumnContract $column) => $column->canDisplay($row))
                    ->toArray(),
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.stack-column');
    }
}
