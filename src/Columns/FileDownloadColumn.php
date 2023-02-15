<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class FileDownloadColumn extends Column
{
    public ?string $collection = null;

    public function collection(string $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $row->getMedia($this->collection ?? $this->getName()),
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.file-download-column');
    }
}
