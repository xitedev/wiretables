<?php

namespace Xite\Wiretables\Buttons;

use Illuminate\Contracts\View\View;

class ActionButton extends Button
{
    protected bool $outside = false;
    protected string $action;
    protected array $params = [];
    protected ?string $component = null;
    protected ?string $confirmation = null;

    public function outside(): self
    {
        $this->outside = true;

        return $this;
    }

    public function action(string $action): self
    {
        $this->action = $action;

        return $this;
    }

    public function component(string $component): self
    {
        $this->component = $component;

        return $this;
    }

    public function confirmation(string $confirmation) : self
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    public function render(): View
    {
        return view('wiretables::buttons.action-button')
            ->with([
                'outside' => $this->outside,
                'action' => $this->action,
                'component' => $this->component,
                'confirmation' => $this->confirmation,
            ]);
    }
}
