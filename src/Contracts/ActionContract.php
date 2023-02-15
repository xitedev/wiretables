<?php

namespace Xite\Wiretables\Contracts;

interface ActionContract
{
    public function setModel(string $model): self;
}
