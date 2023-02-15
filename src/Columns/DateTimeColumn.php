<?php

namespace Xite\Wiretables\Columns;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;

class DateTimeColumn extends Column
{
    public string $format = 'd.m.Y H:i';
    public array $class = ['whitespace-nowrap text-gray-600'];

    public function format(string $format): self
    {
        $this->format = $format;

        return $this;
    }

    public function getValue($row)
    {
        return $row->{$this->getName()};
    }

    public function renderIt($row): ?string
    {
        $date = $this->displayData($row);

        if (! $date instanceof Carbon) {
            return $date;
        }

        return $date->format($this->format);
    }

    public function render(): ?View
    {
        return null;
    }
}
