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

    protected function getValue($row)
    {
        return $row->getMedia($this->collection ?? $this->getName());
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'data' => $this->displayData($row),
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.file-download-column');
    }
}
