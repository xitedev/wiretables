<?php

namespace Xite\Wiretables\Traits;

trait WithSortableColumn
{
    public function updateRowSort($data): void
    {
//        check if model is sortable 'has SortableTrait'
        if (! method_exists($this->model, 'bootSortableTrait')) {
            return;
        }

        $orderColumn = $this->getOrderColumn();

//        check if current sort is sortable field
        if ($this->sort !== $orderColumn) {
            return;
        }

        $items = collect($data)
            ->mapWithKeys(fn ($item) => [$item['order'] => $item['value']])
            ->all();

        if ($this->page > 1) {
            $startOrder = $this->model::query()
                ->whereIn('id', $items)
                ->orderBy('order_column')
                ->value('order_column');

            $this->model::setNewOrder($items, $startOrder);
        }

        $this->model::setNewOrder($items);
    }

    public function getOrderColumn(): string
    {
        return $this->model->determineOrderColumnName();
    }

    public function getUseSortProperty(): bool
    {
        if (! method_exists($this, 'updateRowSort')) {
            return false;
        }

        if (! method_exists($this->model, 'bootSortableTrait')) {
            return false;
        }

        return $this->getOrderColumn() === $this->sort;
    }

    public function moveOrderUp($id): void
    {
        $this->model::find($id)->moveOrderUp();
    }

    public function moveOrderDown($id): void
    {
        $this->model::find($id)->moveOrderDown();
    }
}
