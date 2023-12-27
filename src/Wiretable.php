<?php

namespace Xite\Wiretables;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Xite\Searchable\Filters\SearchFilter;
use Xite\Wiretables\Columns\Column;
use Xite\Wiretables\Contracts\ColumnContract;
use Xite\Wiretables\Contracts\TableContract;
use Xite\Wiretables\Traits\WithActions;
use Xite\Wiretables\Traits\WithButtons;
use Xite\Wiretables\Traits\WithFiltering;
use Xite\Wiretables\Traits\WithPagination;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;
use Xite\Wiretables\Traits\WithSearching;
use Xite\Wiretables\Traits\WithSorting;

abstract class Wiretable extends Component implements TableContract
{
    use WithPagination;

    public ?Model $model = null;
    public bool $withHeader = true;
    public bool $withFooter = true;
    protected $request;

    protected $listeners = [
        '$refresh',
    ];

    #[On('resetTable')]
    public function resetTable(): void
    {
        if (in_array(WithPagination::class, class_uses_recursive($this))) {
            $this->resetPage();
        }

        if (in_array(WithFiltering::class, class_uses_recursive($this))) {
            $this->resetFilter();
        }

        if (in_array(WithSorting::class, class_uses_recursive($this))) {
            $this->resetSort();
        }

        if (in_array(WithSearching::class, class_uses_recursive($this))) {
            $this->resetSearch();
        }
    }

    #[On('refreshTable')]
    public function refreshTable(): void
    {
        return;
    }

    public function getRequest(): QueryBuilderRequest
    {
        if (! $this->request) {
            $this->request = app(QueryBuilderRequest::class);
        }

        return $this->request;
    }

    #[Computed]
    public function getColumns(): Collection
    {
        return $this->columns()
            ->filter(fn ($column) => $column instanceof ColumnContract)
            ->filter(fn ($column) => $column->canRender)
            ->when(
                in_array(WithSearching::class, class_uses_recursive($this)) && $this->searchString,
                fn (Collection $rows) => $rows->each(
                    fn (Column $column) => $column->highlight($this->searchString)
                )
            )
            ->when(
                in_array(WithFiltering::class, class_uses_recursive($this)) && method_exists($this, 'notFilterable'),
                fn (Collection $rows) => $rows->each(
                    fn (Column $column) => $column->notFilterable()
                )
            )
            ->when(
                in_array(WithSorting::class, class_uses_recursive($this)),
                fn (Collection $rows) => $rows->each(
                    fn (Column $column) => $column->currentSort($this->getSort())
                )
            )
            ->when(
                in_array(WithButtons::class, class_uses_recursive($this)) && $actionColumn = $this->getActionColumn(),
                fn (Collection $rows) => $rows->push($actionColumn)
            )
            ->when(
                in_array(WithActions::class, class_uses_recursive($this)) && $checkboxColumn = $this->getCheckboxColumn(),
                fn (Collection $rows) => $rows->prepend($checkboxColumn)
            );
    }

    #[Computed]
    public function getData()
    {
        $builder = QueryBuilder::for($this->query(), $this->getRequest());

        if (in_array(WithFiltering::class, class_uses_recursive($this))) {
            $builder = $builder->allowedFilters(
                $this->allowedFilters->toArray()
            );
        }

        if (in_array(WithSorting::class, class_uses_recursive($this))) {
            $builder = $builder
                ->defaultSort($this->getDefaultSort())
                ->allowedSorts(
                    $this->allowedSorts->toArray()
                );
        }

        if (in_array(WithSearching::class, class_uses_recursive($this)) && (! $this->disableSearch && $this->searchString)) {
            $builder = $builder
                ->tap(
                    new SearchFilter($this->searchString, $this->strict)
                );
        }

        return $builder->paginate($this->perPage)->onEachSide(1);
    }

    abstract public function columns(): Collection;

    abstract public function render(): View;

    abstract public function title(): string;

    abstract protected function query(): Builder|Relation;

}
