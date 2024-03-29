<a href="{{ $route }}"
   @class(['px-2 group flex items-center text-sm justify-center space-x-1 text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150', $class])
   rel="button"
   target="{{ $target }}"
   wire:key="{{ $key }}"
   alt="{{ $title }}"
>
    @if($icon)
        @svg($icon, "h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500")
    @endif
    @if($title)
        <span class="hidden md:inline-block">{{ $title }}</span>
    @endif
</a>
