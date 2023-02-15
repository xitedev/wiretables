@aware([
    'striped' => true
])

<tr {{ $attributes->class(['odd:bg-gray-50' => $striped]) }}>
    {{ $slot }}
</tr>
