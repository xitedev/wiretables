<?php

namespace Xite\Wiretables\Modals;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use LivewireUI\Modal\ModalComponent;

class DeleteModal extends ConfirmModal
{
    use AuthorizesRequests;

    public string $modelName;
    public int $modelId;
    public Model $model;

    #[Computed]
    public function title(): string
    {
        return __('wiretables::modals.delete_title');
    }

    public function getDescriptionProperty(): string
    {
        return __('wiretables::modals.delete_description');
    }

    public function mount(string $modelName, int $modelId): void
    {
        $this->model = app($modelName)->findOrFail($modelId);
    }

    /**
     * @throws AuthorizationException
     */
    public function performSubmit(): void
    {
        $this->authorize('delete', $this->model);

        $this->model->delete();
    }

    public function render(): View
    {
        return view('wiretables::modals.delete-modal');
    }
}
