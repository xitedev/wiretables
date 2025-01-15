<?php

namespace Xite\Wiretables\Filters;

use Illuminate\Support\Str;
use Illuminate\Support\Traits\Conditionable;
use Xite\Wireforms\Traits\Authorizable;
use Xite\Wiretables\Contracts\FilterContract;
use Spatie\QueryBuilder\AllowedFilter;

abstract class Filter extends AllowedFilter implements FilterContract
{
    use Authorizable;
    use Conditionable;

    protected int $size = 2;
    protected ?string $title = null;
    protected ?string $placeholder = null;
    public ?string $value = null;
    protected bool $fillable = true;
    protected bool $fillOnCreate = false;
    protected ?array $relatedFilters = [];

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function placeholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    public function size(int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function value(?string $value = null): self
    {
        $this->value = $value;

        return $this;
    }

    public function fillOnCreate(): self
    {
        $this->fillOnCreate = true;

        return $this;
    }

    public function resetOnChange(...$relatedFilters): self
    {
        $this->relatedFilters = $relatedFilters;

        return $this;
    }

    public function isFillable(): bool
    {
        return $this->fillable;
    }

    public function canBeFilledOnCreate(): bool
    {
        return $this->fillOnCreate;
    }

    public function hasValue(): bool
    {
        return ! is_null($this->value);
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getPlaceholder(): string
    {
        return $this->placeholder;
    }

    public function hasRelatedFilters(): bool
    {
        return count($this->relatedFilters) > 0;
    }

    public function getRelatedFilters(): array
    {
        return $this->relatedFilters;
    }

    public function getKebabName(): string
    {
        return Str::of($this->getName())
            ->camel()
            ->kebab()
            ->replace('.', '-')
            ->prepend('filter-')
            ->toString();
    }

    public function getValue(?string $value = null): ?string
    {
        $newValue = method_exists($this, 'castValue')
            ? $this->castValue($value)
            : $value;

        if (! $this->hasDefault) {
            return $newValue;
        }

        return $newValue !== $this->getDefault() ? $newValue : null;
    }
}
