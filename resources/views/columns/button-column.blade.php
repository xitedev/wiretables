<x-dynamic-component
    :component="'wireforms::button.'.$style"
    wire:click="$emit('openModal', '{{ $modal }}', {'model': {{ $id }}})"
    class="text-gray-500 flex items-center space-x-1 justify-center w-16 mx-auto"
    :outline="$outline"
    icon="heroicon-o-photo"
/>
