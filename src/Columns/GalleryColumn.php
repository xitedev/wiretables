<?php

namespace Xite\Wiretables\Columns;

use Illuminate\Contracts\View\View;

class GalleryColumn extends Column
{
    public string $collection;
    public bool $hasThumbnail = false;
    public int $count = -1;

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

    public function count(int $count): self
    {
        $this->count = $count;

        return $this;
    }

    protected function getValue($row)
    {
        return $row->getMedia($this->collection);
    }

    public function renderIt($row): ?string
    {
        return $this
            ->render()
            ?->with([
                'id' => $row->getKey(),
                'name' => $this->getName(),
                'displayName' => $row->getDisplayName(),
                'media' => $this->displayData($row)->when(
                    $this->count > 0,
                    fn ($media) => $media->take($this->count)
                ),
                'firstImage' => $this->displayData($row)->first()?->getUrl($this->hasThumbnail ? 'thumb' : ''),
            ])
            ->render();
    }

    public function render(): ?View
    {
        return view('wiretables::columns.gallery-column');
    }
}
