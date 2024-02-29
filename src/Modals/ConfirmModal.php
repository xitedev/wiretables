<?php

namespace Xite\Wiretables\Modals;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use LivewireUI\Modal\ModalComponent;
use Xite\Wireforms\Traits\HasComponentName;

abstract class ConfirmModal extends ModalComponent
{
    use HasComponentName;

    #[Computed]
    abstract public function title(): string;

    #[Computed]
    public function description(): string
    {
        return __('wiretables::modals.confirm_description');
    }

    #[Computed]
    public function confirmButton(): string
    {
        return __('wiretables::modals.confirm');
    }

    abstract public function performSubmit(): void;

    public function submit(): void
    {
        $this->performSubmit();

        $this->closeModalWithEvents([
            '$refresh',
        ]);
    }

    public function render(): View
    {
        return view('wiretables::modals.confirm-modal');
    }
}
