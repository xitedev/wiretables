@props([
    'title' => null
])
<div {{ $attributes }}>
    @if($title)
        <div class="p-2 sm:px-3">
            <h3 {{ $title->attributes->class('text-base leading-6 font-medium text-gray-900') }}>
                {{ $title }}
            </h3>
        </div>
    @endif

    <div class="p-2 sm:p-0">
        <dl class="sm:divide-y sm:divide-gray-100">
            {{ $slot }}
        </dl>
    </div>
</div>
