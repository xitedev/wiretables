<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Pagination\Paginator;

trait WithPagination
{
    use \Livewire\WithPagination;

    public int $perPage = 25;

    public array $perPageOptions = [
        10,
        25,
        50,
        100
    ];

    public bool $showPerPageOptions = true;

    public string $paginationView = 'wiretables::partials.pagination';

    public function bootWithPagination(): void
    {
        Paginator::defaultView($this->paginationView);
    }
}
