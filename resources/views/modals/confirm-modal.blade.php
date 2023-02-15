<div class="relative inline-block align-bottom bg-white px-4 pt-5 pb-4 text-left overflow-hidden sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
    <div class="sm:flex sm:items-center">
        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 sm:mx-0 sm:h-10 sm:w-10">
            <svg class="h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>

        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                {{ $this->title }}
            </h3>
            <div class="mt-2">
                <p class="text-sm text-gray-500">
                    {{ $this->description }}
                </p>
            </div>
        </div>
    </div>
    <div class="mt-5 sm:mt-4 sm:ml-10 sm:pl-4 sm:flex">
        <x-wireforms::button.secondary
            wire:click="$emit('closeModal')"
            class="inline-flex justify-center w-full sm:w-auto"
            :title="__('wiretables::modals.close')"
        />

        <x-wireforms::button.primary
            wire:click="submit"
            type="submit"
            class="inline-flex justify-center w-full sm:w-auto sm:ml-3 mt-3 sm:mt-0"
            :title="__('wiretables::modals.confirm')"
        />
    </div>
</div>
