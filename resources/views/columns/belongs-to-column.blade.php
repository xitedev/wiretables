<div class="group flex items-center justify-start space-x-1">
    @if($icon !== false)
        <span @if($filter && $filterValue) class="cursor-pointer" @click.prevent="$wire.addFilterOutside('{{ $filter }}', {{ $filterValue }})" @endif>
              @svg($icon ?? 'heroicon-o-link', 'flex-shrink-0 h-4 w-4 text-gray-300 transition ease-in-out duration-150')
        </span>
    @endif

    @if(!is_null($route) || !is_null($showModal))
        <a class="group inline-flex items-center space-x-1 truncate text-sm leading-5 transition ease-in-out duration-150"
           @if($route)
               href="{{ $route }}"
           target="_blank"
           @elseif($showModal)
               href="#"
           wire:click.prevent="$dispatch('openModal', { component: '{{ $showModal }}', arguments: {{ json_encode(['model' => $value], JSON_THROW_ON_ERROR) }} })"
            @endif
        >
            {{ $data }}
        </a>
    @else
        <span class="leading-5">
             {{ $data }}
        </span>
    @endif

    @if($copyButton)
        <button class="opacity-0 group-hover:opacity-100"
                title="{{ __('admin.copy') }}"
                x-clipboard.raw="{{ $data }}"
        >
            <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z"></path><path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z"></path>
            </svg>
        </button>
    @endif
</div>
