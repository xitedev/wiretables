<div {{ $attributes->class('flex flex-col') }}>
    @if($this->showPerPageOptions || $this->hasFilters || method_exists($this, 'bootWithActions') || (method_exists($this, 'bootWithSearching') && !$this->disableSearch))
        <div
            @if($this->hasFilters)
            x-data="{ filtersAreShown: {{ $this->selectedFiltersCount > 0 ? 'true' : 'false' }} }"
            @toggle-filter.window="filtersAreShown = !filtersAreShown"
            @hide-filter.window="filtersAreShown = false"
            @endif
        >
            <div class="flex justify-end lg:justify-between items-center content-center flex space-x-1 sm:space-x-2 mb-2">
                <div class="space-x-2 items-center hidden lg:flex">
                    @if($this->showPerPageOptions)
                        <label for="per-page" class="text-sm text-gray-500">{{ __('wiretables::table.per_page') }}</label>
                        <select
                            id="per-page"
                            class="block pl-2 w-16 py-1 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 leading-5 bg-white placeholder-gray-300 focus:outline-none focus:placeholder-gray-400 focus:border-primary-300 focus:outline-none focus:ring-primary-500 focus:border-primary-500 dark:focus:border-primary-700 sm:text-sm transition duration-150 ease-in-out rounded-sm"
                            name="perPage"
                            wire:model.live="perPage"
                        >
                            @foreach($this->perPageOptions as $option)
                                <option value="{{ $option }}" @selected($this->perPage)>{{ $option }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>


                <div class="flex space-x-2">
                    @isset($actions)
                        {{ $actions }}
                    @endif

                    @if(method_exists($this, 'bootWithSearching') && !$this->disableSearch)
                        <div class="lg:max-w-sm flex items-center">
                            <label for="search" class="sr-only">{{ __('wiretables::table.search') }}</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-2 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input
                                    id="search"
                                    placeholder="{{ __('wiretables::table.search') }}"
                                    type="search"
                                    value="{{ $this->searchString }}"
                                    wire:model.live.debounce.1000ms="searchString"
                                    class="block w-60 lg:w-96 px-8 py-1 border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 leading-5 bg-white placeholder-gray-300 dark:placeholder-gray-600 focus:outline-none focus:placeholder-gray-400 focus:border-primary-300 dark:focus:border-primary-700 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out rounded-sm"
                                >
                                @if($this->searchString && !$this->disableStrict)
                                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                                        <input name="strict"
                                               type="checkbox"
                                               class="appearance-none h-4 w-4 border border-gray-300 dark:border-primary-700 rounded-sm bg-white dark:bg-gray-700 checked:bg-primary-500 checked:border-primary-500 focus:outline-none transition duration-200 my-1 align-top bg-no-repeat bg-center bg-contain float-left cursor-pointer"
                                               wire:model.live="strict"
                                        >
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @if($this->hasFilters)
                <div
                    class="relative flex justify-between bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 py-2 mb-2 whitespace-nowrap text-gray-700 dark:text-gray-300 grid gap-4 grid-cols-12 align-center items-center rounded-sm"
                    x-show="filtersAreShown"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    x-cloak

                >
                    <div class="absolute w-full h-full top-0 left-0 z-10 bg-white opacity-25" style="display: none;" wire:loading></div>
                    @foreach($this->filledFilters as $filter)
                        <div class="col-span-12 sm:col-span-{{ $filter->getSize() }}" wire:key="filter-{{ $filter->getName() }}">
                            {!! $filter->render() !!}
                        </div>
                    @endforeach
                </div>
            @endif

            @if(method_exists($this, 'bootWithActions'))
                <div
                    x-data="toggleHandler"
                    x-show="checked.length"
                    x-cloak
                    @toggle-check.window="toggleCheck($event.detail)"
                    class="flex justify-between bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 py-2 mb-2 whitespace-nowrap text-gray-700 dark:text-gray-300 grid gap-4 grid-cols-12 align-center items-center rounded-sm"
                    x-transition:enter="transition ease-linear duration-300 transform"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-linear duration-300 transform"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                >
                    @foreach($this->actions as $action)
                        {{ $action->getName() }}
                        {{--                    @livewire($action->getName(), ['model' => $action->getModel(), 'icon' => $action->getIcon(), 'title' => $action->getTitle(), 'size' => $action->getSize()], key($loop->index))--}}
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    <div class="overflow-x-auto align-middle inline-block w-full">
        <x-wiretables::table>
            @if($this->getColumns->filter(fn ($column) => $column->renderTitle())->filter()->count())
                <x-slot name="header">
                    <x-wiretables::table.tr>
                        @foreach($this->getColumns as $column)
                            <x-wiretables::table.th wire:key="title-{{ $loop->index }}" style="width: {{ $column->getWidth() }};">
                                {!! $column->renderTitle() !!}
                            </x-wiretables::table.th>
                        @endforeach
                    </x-wiretables::table.tr>
                </x-slot>
            @endif

            <x-slot name="body"
                    wire:loading.class="opacity-50"
                    class="text-sm"
                    :wire:sortable="(method_exists($this, 'getUseSortProperty') && $this->useSort) ? 'updateRowSort' : null"
                    :wire:sortable.options="(method_exists($this, 'getUseSortProperty') && $this->useSort) ? '{ animation: 100 }' : null"
            >
                @forelse($this->getData->items() as $row)
                    <x-wiretables::table.tr
                        id="row-{{ $row->getKey() }}"
                        wire:key="row-{{ $row->getKey() }}"
                        :wire:sortable.item="(method_exists($this, 'getUseSortProperty') && $this->useSort) ? $row->getKey() : null"
                    >
                        @foreach($this->getColumns as $column)
                            <x-wiretables::table.td
                                :class="$column->getClass($row)"
                                wire:key="p-column-{{ $row->getKey() }}-{{ $loop->index }}"
                            >
                                @if($column->canDisplay($row))
                                    {!! $column->renderIt($row) !!}
                                @endif
                            </x-wiretables::table.td>
                        @endforeach
                    </x-wiretables::table.tr>
                @empty
                    <x-wiretables::table.tr>
                        <x-wiretables::table.td class="whitespace-nowrap border-b border-gray-200 dark:border-gray-800" colspan="100">
                            <div class="flex flex-col gap-2 items-center text-gray-500 dark:text-gray-300 justify-center">
                                <div>
                                    @lang('wiretables::table.table_is_empty')
                                </div>

                                @if((method_exists($this, 'bootWithSearching') && $this->searchString) || (method_exists($this, 'mountWithFiltering') && $this->selectedFiltersCount > 0))
                                    <div>
                                        @lang('wiretables::table.reset_filters')
                                        <a href="#" class="text-primary-700 dark:text-primary-400" @click.prevent="$wire.call('resetTable') && $dispatch('hide-filter')">@lang('wiretables::table.reset')</a>
                                    </div>
                                @endif
                            </div>
                        </x-wiretables::table.td>
                    </x-wiretables::table.tr>
                @endforelse
            </x-slot>
        </x-wiretables::table>
    </div>

    <div class="w-full overflow-x-auto">
        {{ $this->getData->links() }}
    </div>
</div>
