<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

trait WithSortableColumn
{
    public function updateRowSort($data): void
    {
//        check if model is sortable 'has SortableTrait'
        if (! method_exists($this->model, 'bootSortableTrait')) {
            return;
        }

        $orderColumn = $this->model->determineOrderColumnName();
        $primaryKeyColumn = $this->model->getKeyName();

//        check if current sort is sortable field
        if ($this->sort !== $orderColumn) {
            return;
        }

//        get sort before sorting
        $currentOrder = $this->model::query()
            ->whereIn($primaryKeyColumn, collect($data)->pluck('value'))
            ->ordered()
            ->withoutEagerLoads()
            ->get([
                $primaryKeyColumn,
                $orderColumn
            ])
            ->pluck($orderColumn, $primaryKeyColumn);

//        determinate start order
        $startOrder = $currentOrder->first();

//        reorder
        $items = collect($data)
            ->each(function ($item) use (&$startOrder) {
                $item['order'] = $startOrder++;
            })
            ->pluck('order', 'value')
            ->diffAssoc($currentOrder)
            ->each(
                fn ($order, $id) => $this->model::withoutGlobalScope(SoftDeletingScope::class)
                    ->where($primaryKeyColumn, $id)
                    ->update([$orderColumn => $order])
            );

        $this->afterRowSort($items);
    }

    public function getUseSortProperty(): bool
    {
        if (! method_exists($this, 'updateRowSort')) {
            return false;
        }

        if (! method_exists($this->model, 'bootSortableTrait')) {
            return false;
        }

        return $this->model->determineOrderColumnName() === $this->sort;
    }

    public function moveOrderUp($id): void
    {
        $this->model::find($id)->moveOrderUp();
    }

    public function moveOrderDown($id): void
    {
        $this->model::find($id)->moveOrderDown();
    }

    public function afterRowSort(Collection $items): void
    {

    }
}
