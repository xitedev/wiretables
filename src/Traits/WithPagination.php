<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Pagination\Paginator;
use Livewire\Features\SupportPagination\HandlesPagination;

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

    public function bootWithPagination(): void
    {
//        $this->setPage($this->page);
//
//        $this->perPage = $this->defaultPerPage;
//
//        if (!in_array($this->defaultPerPage, $this->perPageOptions, true)) {
//            $this->perPageOptions[] = $this->defaultPerPage;
//
//            sort($this->perPageOptions);
//        }
//
//        Paginator::currentPageResolver(fn () => $this->page);

        Paginator::defaultView('wiretables::partials.pagination');
    }
}
