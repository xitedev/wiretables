<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Xite\Wiretables\Contracts\ColumnContract;

trait WithSorting
{
    public string $defaultSort = '-id';

    public $sorts = [];

    public function queryStringWithSorting()
    {
        return collect($this->sorts)
            ->mapWithKeys(fn ($sort, $sortName) => [
                'sorts.'.$sortName => [
                    'history' => true,
                    'as' => $sortName,
                    'keep' => false
                ]
            ])
            ->toArray();
    }

    public function bootWithSorting(): void
    {
        $defaultSort = Str::of($this->defaultSort)->replaceStart('-', '');

        $this->sort = $this->allowedSorts->contains($defaultSort)
            ? $this->defaultSort
            : $this->allowedSorts->first();
    }

    protected function resetSort(): void
    {
        $this->setSort($this->getDefaultSort());
    }

    private function setSort(string $sort, string $sortName = 'sort'): void
    {
        $this->sorts[$sortName] = $sort;

        $this->getRequest()->query->set($sortName, $sort);
    }

    #[Computed(persist: true)]
    public function allowedSorts(): Collection
    {
        return $this->columns()
            ->filter(fn (ColumnContract $column) => $column->isSortable())
            ->map(fn (ColumnContract $column) => $column->getSortableField())
            ->values();
    }

    public function sortBy($columnName): void
    {
        $sortBy = ($this->sort !== $columnName)
            ? $columnName
            : sprintf('-%s', $columnName);

        $this->setSort($sortBy);

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
