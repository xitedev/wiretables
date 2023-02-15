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

    private array $actionButtons = [];

    public array $createButtonParams = [];
    public ?string $createButton = null;
    public ?string $showButton = null;
    public ?string $editButton = null;

    public function bootWithButtons(): void
    {
//
    }

    public function showDeleteButton(): bool
    {
        return true;
    }

    public function showRestoreButton(): bool
    {
        return method_exists($this->model, 'bootSoftDeletes');
    }

    protected function can(string $ability, Model|string $model): bool
    {
        try {
            $this->authorize($ability, $model);

            return true;
        } catch (AuthorizationException) {
            return false;
        }
    }

    public function getAllowedButtonsProperty(): Collection
    {
        return $this->buttons()
            ->reject(fn (ButtonContract $button) => $button->isGlobal())
            ->when(
                $this->showButton,
                fn ($buttons) => $buttons->push(
                    LinkButton::make('show')
                        ->icon('heroicon-o-eye')
                        ->routeUsing(fn ($row) => route($this->showButton, $row))
                        ->displayIf(fn ($row) => $this->can('view', $row))
                )
            )
            ->when(
                $this->editButton,
                fn ($buttons) => $buttons->push(
                    ModalButton::make('edit')
                        ->icon('heroicon-o-pencil')
                        ->modal($this->editButton)
                        ->withParams(fn ($row) => [
                            'model' => $row->getKey(),
                        ])
                        ->displayIf(fn ($row) => $this->can('update', $row))
                )
            )
            ->when(
                $this->showDeleteButton(),
                fn ($buttons) => $buttons->push(
                    ModalButton::make('delete')
                        ->icon('heroicon-o-trash')
                        ->modal(DeleteModal::getName())
                        ->withParams(fn ($row) => [
                            'modelName' => get_class($row),
                            'modelId' => $row->getKey(),
                        ])
                        ->displayIf(fn ($row) => $this->can('delete', $row))
                )
            )
            ->when(
                $this->showRestoreButton(),
                fn ($buttons) => $buttons->push(
                    ModalButton::make('restore')
                        ->icon('heroicon-o-reply')
                        ->modal(RestoreModal::getName())
                        ->withParams(fn ($row) => [
                            'modelName' => get_class($row),
                            'modelId' => $row->getKey(),
                        ])
                        ->displayIf(fn ($row) => $this->can('restore', $row))
                )
            )
            ->filter(fn ($button) => $button instanceof ButtonContract);
    }

    public function getCreateButtonParams(): array
    {
        $buttons = [];

        if (method_exists($this, 'mountWithFiltering')) {
            $buttons['fillFields'] = $this->getAllowedFiltersProperty()
                ->filter(fn (FilterContract $filter) => $filter->canBeFilledOnCreate() && ! is_null($filter->value))
                ->mapWithKeys(fn (FilterContract $filter) => [$filter->getName() => $filter->value])
                ->toArray();
        }

        return array_filter(
            array_merge_recursive(
                $this->createButtonParams,
                $buttons
            )
        );
    }

    public function getGlobalButtonsProperty(): Collection
    {
        return $this->buttons()
            ->filter(fn (ButtonContract $button) => $button->isGlobal())
            ->when(
                $this->createButton,
                fn ($buttons) => $buttons->push($this->getCreateButton())
            )
            ->filter(fn ($button) => $button instanceof ButtonContract)
            ->filter(fn ($button) => $button->canDisplay());
    }

    protected function getCreateButton(): ButtonContract
    {
        return ModalButton::make('create')
            ->icon('heroicon-o-plus')
            ->title(__('wiretables::table.add'))
            ->modal($this->createButton)
            ->withParams(fn () => $this->getCreateButtonParams())
            ->displayIf(fn () => $this->can('create', $this->model));
    }

    protected function getActionColumn(): ?ColumnContract
    {
        if (! $this->allowedButtons->count()) {
            return null;
        }

        return ActionColumn::make('action')
            ->withButtons($this->allowedButtons);
    }

    protected function buttons(): Collection
    {
        return collect();
    }
}
