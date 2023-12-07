<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\ComponentHookRegistry;
use Livewire\Features\SupportQueryString\BaseUrl;
use Livewire\Features\SupportQueryString\SupportQueryString;
use Xite\Wiretables\Contracts\ColumnContract;

trait WithSorting
{
    public string $defaultSort = '-id';

    public $sorts = [];

    public function queryStringWithSorting(): array
    {
        return collect($this->sorts)
            ->mapWithKeys(fn ($sort, $sortName) => [
                'sorts.'.$sortName => [
                    'history' => true,
                    'as' => $sortName,
                    'keep' => false,
                    'except' => $this->getDefaultSort()
                ]
            ])
            ->toArray();
    }

    public function bootWithSorting(): void
    {
        $this->ensureSorterIsInitialized();
    }

    private function ensureSorterIsInitialized($sortName = 'sort'): void
    {
        if (isset($this->sorts[$sortName])) return;

        $queryStringDetails = $this->getQueryStringDetails($sortName);

        $defaultSort = $this->allowedSorts->contains(Str::of($this->defaultSort)->replaceStart('-', ''))
            ? $this->defaultSort
            : $this->allowedSorts->first();

        $this->sorts[$sortName] = $this->resolveSort($queryStringDetails['as'], $defaultSort);

        $this->addUrlHook($sortName, $queryStringDetails);
    }

    private function getQueryStringDetails($sortName)
    {
        $sortNameQueryString = data_get($this->getQueryString(), 'sorts.' . $sortName);

        $sortNameQueryString['as'] ??= $sortName;
        $sortNameQueryString['history'] ??= true;
        $sortNameQueryString['keep'] ??= false;

        return $sortNameQueryString;
    }

    private function resolveSort($alias, $default)
    {
        return request()->query($alias, $default);
    }

    private function addUrlHook($sortName, $queryStringDetails)
    {
        $key = 'sorts.' . $sortName;
        $alias = $queryStringDetails['as'];
        $history = $queryStringDetails['history'];
        $keep = $queryStringDetails['keep'];

        $this->setPropertyAttribute($key, new BaseUrl(as: $alias, history: $history, keep: $keep));
    }

    public function resetSort(): void
    {
        $this->setSort($this->getDefaultSort());
    }

    private function getQueryString()
    {
        $supportQueryStringHook = ComponentHookRegistry::getHook($this, SupportQueryString::class);

        return $supportQueryStringHook->getQueryString();
    }

    private function setSort(string $sort, string $sortName = 'sort'): void
    {
        $this->sorts[$sortName] = $sort;

        $this->getRequest()->query->set($sortName, $sort);
    }

    public function getSort($sortName = 'sort')
    {
        return $this->sorts[$sortName];
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
        $sortBy = ($this->getSort() !== $columnName)
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
