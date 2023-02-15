@props([
    'header',
    'block',
    'actions',
    'footer',
])

<div {{ $attributes->class(['border border-gray-200 bg-white']) }}>
    @isset($header)
        <div {{ $header->attributes->class(['border-b border-gray-200 bg-gray-100 flex justify-between']) }}>
            <span class="px-4 py-3 text-gray-700">{{ $header }}</span>

            @isset($actions)
                <div {{ $actions->attributes->class(['divide-x divide-gray-200 flex items-center border-l border-gray-200']) }}>
                    {{ $actions }}
                </div>
            @endisset
        </div>
    @endisset

    @isset($block)
        {{ $block }}
    @endisset

    {{ $slot }}

    @isset($footer)
        <div {{ $footer->attributes->class(['border-t border-gray-200 bg-gray-50 p-3']) }}>
            {{ $footer }}
        </div>
    @endisset
</div>
