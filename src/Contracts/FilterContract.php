<?php

namespace Xite\Wiretables\Contracts;

use Illuminate\Contracts\View\View;

interface FilterContract
{
    public function getName(): string;

    public function canBeFilledOnCreate(): bool;

    public function fillOnCreate(): self;

    public function resetOnChange(...$relatedFilters): self;

    public function title(string $title): self;

    public function isFillable(): bool;

    public function placeholder(string $placeholder): self;

    public function size(int $size): self;

    public function value(?string $value = null): self;

    public function hasRelatedFilters(): bool;

    public function getRelatedFilters(): array;

    public function render(): View;
}
