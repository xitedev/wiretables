<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Xite\Wiretables\Buttons\LinkButton;
use Xite\Wiretables\Buttons\ModalButton;
use Xite\Wiretables\Columns\ActionColumn;
use Xite\Wiretables\Contracts\ButtonContract;
use Xite\Wiretables\Contracts\ColumnContract;
use Xite\Wiretables\Contracts\FilterContract;
use Xite\Wiretables\Modals\DeleteModal;
use Xite\Wiretables\Modals\RestoreModal;

trait WithButtons
{
    use AuthorizesRequests;

    protected function can(string $ability, Model|string $model): bool
    {
        try {
            $this->authorize($ability, $model);

            return true;
        } catch (AuthorizationException) {
            return false;
        }
    }

    public function globalButtons(): Collection
    {
        return $this->buttons()
            ->filter(fn ($button) => $button instanceof ButtonContract)
            ->filter(fn (ButtonContract $button) => $button->isGlobal())
            ->filter(fn ($button) => $button->canDisplay());
    }

    public function actionButtons(): Collection
    {
        return $this->buttons()
            ->filter(fn ($button) => $button instanceof ButtonContract)
            ->reject(fn (ButtonContract $button) => $button->isGlobal());
    }

    protected function getActionColumn(): ?ColumnContract
    {
        return ActionColumn::make('action')
            ->withButtons(
                $this->actionButtons()
            );
    }

    protected function buttons(): Collection
    {
        return collect();
    }
}
