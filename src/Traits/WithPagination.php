<?php

namespace Xite\Wiretables\Traits;

use Illuminate\Pagination\Paginator;

trait WithPagination
{
    public int $page = 1;
    public int $perPage = 25;

    public int $defaultPerPage = 25;
    public array $perPageOptions = [
        10,
        25,
        50,
        100
    ];

    public bool $simplePagination = false;
    public string $pageKey = 'page';

    public function bootWithPagination(): void
    {
        $this->setPage($this->page);

        $this->perPage = $this->defaultPerPage;

        if (!in_array($this->defaultPerPage, $this->perPageOptions, true)) {
            $this->perPageOptions[] = $this->defaultPerPage;

            sort($this->perPageOptions);
        }

        Paginator::currentPageResolver(fn () => $this->page);

        Paginator::defaultView($this->paginationView());
        Paginator::defaultSimpleView($this->simplePaginationView());
    }

    public function queryStringWithPagination(): array
    {
        return [
            'page' => [
                'except' => 1,
                'as' => $this->pageKey,
            ],
            'perPage' => [
                'except' => $this->defaultPerPage
            ]
        ];
    }

    protected function paginationView(): string
    {
        return 'wiretables::partials.pagination';
    }

    protected function simplePaginationView(): string
    {
        return 'wiretables::partials.simple-pagination';
    }

    protected function resetPage(): void
    {
        $this->setPage(1);
    }

    private function setPage($page): void
    {
        $this->page = (int) $page;
    }

    public function gotoPage($page): void
    {
        $this->setPage($page);
    }

    public function previousPage(): void
    {
        if ($this->getPage() === 1) {
            return;
        }

        $this->setPage(--$this->page);
    }

    public function nextPage(): void
    {
        $this->setPage(++$this->page);
    }
}
