<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Xite\Wiretables\Contracts\ColumnContract;

trait WithSorting
{
    public string $sort = '-id';
    public string $sortKey = 'sort';

    public string $defaultSort = '-id';

    public function bootWithSorting(): void
    {
        $defaultSort = Str::of($this->defaultSort)->replaceFirst('-', '');

        $this->sort = $this->getAllowedSortsProperty()->contains($defaultSort)
            ? $this->defaultSort
            : $this->getAllowedSortsProperty()->first();
    }

    public function hydrateWithSorting(): void
    {
        $this->setSort($this->sort);
    }

    public function mountWithSorting(): void
    {
        $this->setSort($this->sort);
    }

    public function queryStringWithSorting(): array
    {
        return [
            'sort' => [
                'except' => $this->getDefaultSort(),
                'as' => $this->sortKey,
            ],
        ];
    }

    protected function resetSort(): void
    {
        $this->setSort($this->getDefaultSort());
    }

    private function setSort($sort): void
    {
        $this->sort = (string) $sort;

        $this->getRequest()->query->set($this->sortKey, (string) $sort);
    }

    public function getAllowedSortsProperty(): Collection
    {
        return $this->columns()
                ->filter(fn (ColumnContract $column) => $column->isSortable())
                ->map(fn (ColumnContract $column) => $column->getSortableField())
                ->values();
    }

    public function sortBy($columnName): void
    {
        $this->setSort(
            ($this->sort !== $columnName)
                ? $columnName
                : sprintf('-%s', $columnName)
        );

        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    public function getDefaultSort(): string
    {
        return property_exists($this, 'defaultSort')
            ? $this->defaultSort
            : sprintf('-%s', $this->columns()->first()->getName());
    }
}
