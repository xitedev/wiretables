<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use Xite\Wiretables\Traits\HasClipboard;
use Xite\Wiretables\Traits\HasFilterable;
use Xite\Wiretables\Traits\HasIcon;

class BatchUuidColumn extends Column
{
    use HasFilterable;
    use HasIcon;
    use HasClipboard;

    private bool $showValue = false;

    public function showValue(): self
    {
        $this->showValue = true;

        return $this;
    }

    public function renderIt($row): ?string
    {
        $filter = $this->getFilterableField($row);
        $value = $this->getValue($row);

        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'value' => $value,
                'icon' => $this->getIcon(),
                'showValue' => $this->showValue,
                'clipboard' => $this->getClipboard($row),
                'filter' => $filter,
                'filterValue' => ! is_null($filter) && $filter !== $this->getName()
                    ? data_get($row->toArray(), $filter)
                    : $value,
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.batch-uuid-column');
    }
}
