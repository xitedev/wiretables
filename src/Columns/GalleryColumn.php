<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class GalleryColumn extends Column
{
    public string $collection;
    public bool $hasThumbnail = false;

    public function collection(string $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function hasThumbnail(): self
    {
        $this->hasThumbnail = true;

        return $this;
    }

    public function renderIt($row): ?string
    {
        $media = $row->getMedia($this->collection);

        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'displayName' => $row->getDisplayName(),
                'media' => $media,
                'firstImage' => $media->first()->getUrl($this->hasThumbnail ? 'thumb' : ''),
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.gallery-column');
    }
}
