@props([
    'striped' => true
])

<table {{ $attributes->merge(['class' => "w-full overflow-x-scroll rounded-sm"]) }}>
    @isset($header)
        <thead {{ $header->attributes->merge(['class' => 'border-x border-t border-gray-200']) }}>
        {{ $header }}
        </thead>
    @endisset

    @isset($body)
        <tbody {{ $body->attributes->merge(['class' => 'border border-gray-200 bg-white leading-6 md:leading-5 text-gray-700']) }}>
        {{ $body }}
        </tbody>
    @endisset

    @isset($footer)
        <tfoot {{ $footer->attributes->merge(['class' => 'border-x border-b border-gray-200']) }}>
        {{ $footer }}
        </tfoot>
    @endisset
</table>
