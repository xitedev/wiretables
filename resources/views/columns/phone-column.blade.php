@if($data)
    <div class="group flex items-center space-x-1">
        @if($asLink)
            <a href="tel:{{ $data->formatE164() }}" class="font-medium leading-5">
                {{ $data->formatInternational() }}
            </a>
        @else
            <span>
                {{ $data->formatInternational() }}
            </span>
        @endif

        @if($copyButton)
            <button class="opacity-0 group-hover:opacity-100"
                    title="@lang('wiretables::table.copy')"
                    x-clipboard.raw="{{ $data->formatE164() }}"
            >
                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 2a1 1 0 000 2h2a1 1 0 100-2H8z"></path><path d="M3 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v6h-4.586l1.293-1.293a1 1 0 00-1.414-1.414l-3 3a1 1 0 000 1.414l3 3a1 1 0 001.414-1.414L10.414 13H15v3a2 2 0 01-2 2H5a2 2 0 01-2-2V5zM15 11h2a1 1 0 110 2h-2v-2z"></path>
                </svg>
            </button>
        @endif
    </div>
@endif
