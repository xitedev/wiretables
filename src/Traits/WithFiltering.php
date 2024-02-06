<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Xite\Wiretables\Contracts\FilterContract;

use Xite\Wiretables\Filters\TrashedFilter;

trait WithFiltering
{
    public string $filter = '';
    protected Collection $filters;
    protected static string $filterKey = 'filter';

    public function hydrateWithFiltering(): void
    {
        $this->resolveFilters();
        $this->updateFilters();
    }

    public function mountWithFiltering(): void
    {
        $this->resolveFilters();
        $this->updateFilters();
    }

    public function queryStringWithFiltering(): array
    {
        return [
            'filter' => [
                'except' => '',
                'as' => self::$filterKey,
                'keep' => false
            ],
        ];
    }

    private function resolveFilters(): void
    {
        $this->filters = $this->expandFilters();
    }

    private function updateFilters(): void
    {
        $this->filter = $this->shrinkFilters();

        $this->getRequest()->query->set(self::$filterKey, $this->filters->toArray());
    }

    private function expandFilters(): Collection
    {
        if (! $this->filter) {
            return collect();
        }

        return Str::of($this->filter)
            ->explode(';')
            ->mapWithKeys(static function ($filter) {
                if (! str_contains($filter, ':')) {
                    return [$filter => true];
                }

                [$k, $v] = explode(':', $filter);

                return [$k => $v];
            });
    }

    private function shrinkFilters(): string
    {
        return $this->filters
            ->map(fn ($filter, $key) => implode(':', [$key, $filter]))
            ->implode(';');
    }

    protected function filters(): Collection
    {
        return collect();
    }

    protected function resetFilter(): void
    {
        $this->getFilters
            ->filter(fn (FilterContract $filter) => $filter->isFillable() && $this->filters->has($filter->getName()))
            ->each(fn (FilterContract $filter) => $this->dispatchFilterUpdate($filter->getKebabName(), null));

        $this->filters = collect();

        $this->updateFilters();
    }

    private function dispatchFilterUpdate($filter, $value, bool $trigger = false): void
    {
        $this->dispatch('update-' . $filter, value: $value, trigger: $trigger);
    }

    private function performFilterUpdate(FilterContract $filter, $value): void
    {
        $castedValue = $filter->getValue($value);

        $this->filters = $this->filters
            ->put($filter->getName(), $castedValue)
            ->filter();

        if ($filter->hasRelatedFilters()) {
            $selectedFilters = $this->filters->intersectByKeys(
                array_flip($filter->getRelatedFilters())
            );

            foreach ($selectedFilters->keys() as $selected) {
                $selectedFilter = $this->getFilters
                    ->first(fn (FilterContract $row) => $row->getName() === $selected);

                $this->filters->forget($selectedFilter->getName());

                $this->dispatchFilterUpdate($selectedFilter->getKebabName(), null);
            }
        }

        $this->updateFilters();
    }

    #[On('addFilterOutside')]
    public function addFilterOutside(string $key, $value): void
    {
        $filter = $this->getFilters
            ->first(fn (FilterContract $row) => $row->getName() === $key);

        if (! $filter) {
            return;
        }

        $this->performFilterUpdate($filter, $value);

        if ($filter->isFillable()) {
            $this->dispatchFilterUpdate($filter->getKebabName(), $value);
        }
    }

    #[On('addFilter')]
    public function addFilter(string $key, $value): void
    {
        $filter = $this->getFilters
            ->first(fn (FilterContract $row) => $row->getName() === $key);

        if (! $filter) {
            return;
        }

        $this->performFilterUpdate($filter, $value);

        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }

    #[Computed]
    protected function getFilters(): Collection
    {
        return $this->filters()
            ->filter(
                fn (FilterContract $filter) => ! method_exists($filter, 'canSee') || $filter->canRender
            );
    }

    #[Computed]
    public function filledFilters(): Collection
    {
        return $this->getFilters
            ->each(
                fn (FilterContract $filter) => $filter->value(
                    $this->filters->get($filter->getName())
                )
            );
    }

    #[Computed]
    public function selectedFiltersCount(): int
    {
        return $this->filters->count();
    }

    public function hasFilter(string $filter, mixed $value = null): bool
    {
        if (! isset($this->filters[$filter])) {
            return false;
        }

        return is_null($value) || $this->filters[$filter] === $value;
    }
}
