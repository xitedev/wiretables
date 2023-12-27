<?php

namespace Xite\Wiretables\Traits;

trait WithSearching
{
    public string $searchString = '';
    public bool $disableSearch = false;
    public bool $disableStrict = false;
    public bool $strict = false;
    protected static string $searchKey = 'search';

    public function bootWithSearching(): void
    {
    }

    public function queryStringWithSearching(): array
    {
        return [
            'searchString' => [
                'except' => '',
                'as' => self::$searchKey,
                'keep' => false
            ],
            'strict' => [
                'except' => false,
            ],
        ];
    }

    protected function resetSearch(): void
    {
        $this->searchString = '';
        $this->strict = false;
    }

    public function updatedSearch(): void
    {
        if (method_exists($this, 'resetPage')) {
            $this->resetPage();
        }
    }
}
