<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Support\Collection;
use Xite\Wiretables\Columns\CheckboxColumn;
use Xite\Wiretables\Contracts\ActionContract;

trait WithActions
{
    public function bootWithActions(): void
    {
//
    }

    public function getActionsProperty(): Collection
    {
        return collect($this->actions())
            ->each(
                fn (ActionContract $action) => $action->setModel($this->model)
            );
    }

    protected function getCheckboxColumn(): ?CheckboxColumn
    {
        if (! count($this->actions())) {
            return null;
        }

        return CheckboxColumn::make('checkbox');
    }

    protected function actions(): array
    {
        return [];
    }
}
