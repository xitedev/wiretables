@props([
    'striped' => true
])

<table {{ $attributes->merge(['class' => "w-full overflow-x-scroll rounded-sm"]) }}>
    @isset($header)
        <thead {{ $header->attributes->merge(['class' => 'border-x border-t border-gray-200 dark:border-gray-800']) }}>
        {{ $header }}
        </thead>
    @endisset

    @isset($body)
        <tbody {{ $body->attributes->merge(['class' => 'border border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 leading-6 md:leading-5 text-gray-700 dark:text-gray-300']) }}>
        {{ $body }}
        </tbody>
    @endisset

    @isset($footer)
        <tfoot {{ $footer->attributes->merge(['class' => 'border-x border-b border-gray-200 dark:border-gray-800']) }}>
        {{ $footer }}
        </tfoot>
    @endisset
</table>
