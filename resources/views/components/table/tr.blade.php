@aware([
    'striped' => true
])

<tr {{ $attributes->class(['odd:bg-gray-50 dark:odd:bg-gray-800/50' => $striped]) }}>
    {{ $slot }}
</tr>
