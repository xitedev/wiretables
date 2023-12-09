<x-wiretables::card>
    @if($this->withHeader)
        <x-slot name="header">
            {{ $this->title }}
        </x-slot>

        <x-slot name="actions">
            @if(method_exists($this, 'mountWithFiltering') && $this->allowedFilters?->count())
                <button
                    class="group h-full px-3 text-gray-400"
                    @click.prevent="$dispatch('toggle-filter')"
                >
                    <svg class="w-5 h-5 group-hover:text-gray-500 group-focus:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                </button>
            @endif

            <button
                class="group h-full px-3 text-gray-400"
                @click.prevent="$wire.call('resetTable')"
            >
                <svg class="w-5 h-5 group-hover:text-gray-500 group-focus:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="none">
                    <path d="M11.1968 2.43934C11.7826 1.85355 12.7323 1.85355 13.3181 2.43934L17.5607 6.68198C18.1465 7.26777 18.1465 8.21751 17.5607 8.8033L9.36383 17.0002H14.4999C14.776 17.0002 14.9999 17.2241 14.9999 17.5002C14.9999 17.7764 14.776 18.0002 14.4999 18.0002H7.8195C7.40971 18.0217 6.99279 17.876 6.67978 17.5629L2.43714 13.3203C1.85136 12.7345 1.85136 11.7848 2.43714 11.199L11.1968 2.43934ZM12.611 3.14645C12.4157 2.95118 12.0992 2.95118 11.9039 3.14645L5.53822 9.51212L10.488 14.4619L16.8536 8.09619C17.0489 7.90093 17.0489 7.58435 16.8536 7.38909L12.611 3.14645ZM9.78086 15.169L4.83111 10.2192L3.14425 11.9061C2.94899 12.1014 2.94899 12.4179 3.14425 12.6132L7.38689 16.8558C7.58215 17.0511 7.89873 17.0511 8.094 16.8558L9.78086 15.169Z" fill="currentColor"></path>
                </svg>
            </button>

            <button
                class="group h-full px-3 text-gray-400 !rounded-none"
                wire:click.prevent="$refresh"
                @refresh-table.window="@this.call('$refresh')"
            >
                <svg class="w-5 h-5 group-hover:text-gray-500 group-focus:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                </svg>
            </button>

            @if($this->globalButtons)
                @foreach($this->globalButtons as $button)
                    {!! $button->renderIt() !!}
                @endforeach
            @endif
        </x-slot>
    @endif

    <x-wiretables::wiretable class="p-2" />

    @if($this->withFooter)
        <x-slot name="footer">
            {{ $this->total }}
        </x-slot>
    @endif
</x-wiretables::card>
