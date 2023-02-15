<div class="flex flex-row space-x-1 items-center">
    @if($filter && $filterValue)
        <span class="cursor-pointer" @click.prevent="$wire.addFilterOutside('{{ $filter }}', '{{ $filterValue }}')">
            @svg($icon ?? 'heroicon-o-filter', 'flex-shrink-0 h-4 w-4 text-gray-300 transition ease-in-out duration-150')
        </span>
    @endif

    @if($showValue)
        <span @if($clipboard) @click.prevent @dblclick="$clipboard('{{ $clipboard }}')" @endif>{{ $value }}</span>
    @endif
</div>
