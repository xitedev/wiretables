<div class="relative w-16">
    @if($media->count() > 1)
        <div class="absolute -right-1 -top-1">
            <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xxs font-medium bg-green-300 text-green-900">
                {{ $media->count() }}
            </span>
        </div>
    @endif

    <div class="flex justify-center items-center" id="gallery-{{ $id }}" x-lightgallery>
        @foreach($media as $image)
            @if($loop->first)
                <a href="{{ $image->getUrl() }}" >
                    <img class="w-16 h-9 rounded object-cover" loading="lazy" alt="{{ $displayName }}" src="{{ $firstImage }}" />
                </a>
            @else
                <a href="{{ $image->getUrl() }}" />
            @endif
        @endforeach
    </div>
</div>
