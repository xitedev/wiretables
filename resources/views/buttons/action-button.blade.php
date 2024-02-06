<button
    @class([$class, 'px-2 group flex items-center text-sm justify-center space-x-1 text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150'])
    @if($component)
        wire:click.prevent="$dispatchTo('{{ $component  }}', '{{ $action }}', {{ json_encode($params) }})"
    @else
        wire:click.prevent="{{ $action }}({{ json_encode($params) }})"
    @endif
    @if($confirmation)
        wire:confirm="{{ $confirmation }}"
    @endif
    rel="button"
    wire:key="{{ $key }}"
>
    @if($icon)
        @svg($icon, "h-5 w-5")
    @endif
    @if($title)
        <span class="hidden sm:inline-block">{{ $title }}</span>
    @endif
</button>
