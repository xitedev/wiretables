@props([
    'dt',
    'dd'
])

<div {{ $attributes->class('p-2 sm:grid sm:grid-cols-3 sm:gap-2') }}>
    @isset($dt)
        <dt {{ $dt->attributes->class('font-medium text-gray-500') }}>
            {{ $dt }}
        </dt>
    @endisset
    @isset($dd)
        <dd {{ $dd->attributes->class('font-semibold text-gray-900 sm:col-span-2') }}>
            {{ $dd }}
        </dd>
    @endisset
</div>
