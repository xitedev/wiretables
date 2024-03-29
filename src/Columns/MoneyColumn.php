<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;
use NumberFormatter;
use Xite\Wiretables\Traits\HasPopover;

class MoneyColumn extends Column
{
    use HasPopover;

    public string $currency;
    public bool $hideSymbol = false;
    public bool $showSign = false;
    public bool $hideZero = false;
    protected array $class = ['font-bold'];

    public function hideSymbol(): self
    {
        $this->hideSymbol = true;

        return $this;
    }

    public function hideZero(): self
    {
        $this->hideZero = true;

        return $this;
    }

    public function showSign(): self
    {
        $this->showSign = true;

        return $this;
    }

    public function getAmount($value): string
    {
        if ($this->showSign && $value->isNegative()) {
            $value = $value->absolute();
        }

        $formatter = new NumberFormatter('ru_RU', NumberFormatter::CURRENCY);
        $formatter->setAttribute(NumberFormatter::MIN_FRACTION_DIGITS, 2);

        if ($this->hideSymbol) {
            $formatter->setSymbol(NumberFormatter::CURRENCY_SYMBOL, '');
            $formatter->setSymbol(NumberFormatter::INTL_CURRENCY_SYMBOL, '');
        }

        return $formatter->formatCurrency(
            $value->getAmount() / 100,
            $value->getCurrency()->getCode()
        );
    }

    protected function getValue($row)
    {
        return $row->{$this->getName()};
    }

    public function canDisplay($row): bool
    {
        if (! parent::canDisplay($row)) {
            return false;
        }

        $value = $this->displayData($row);

        return !($this->hideZero && $value->isZero());
    }

    public function renderIt($row): ?string
    {
        $value = $this->displayData($row);

        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $value,
                'showSign' => $this->showSign,
                'amount' => $this->getAmount($value),
                'popover' => $this->getPopover($row)
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.money-column');
    }
}
