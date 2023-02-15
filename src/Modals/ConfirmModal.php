<?php

namespace Xite\Wiretables\Modals;

use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use LivewireUI\Modal\ModalComponent;

abstract class ConfirmModal extends ModalComponent
{
    use AuthorizesRequests;

    abstract public function getTitleProperty(): string;

    public function getDescriptionProperty(): string
    {
        return __('wiretables::modals.confirm_description');
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
