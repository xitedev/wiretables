<div class="whitespace-nowrap inline-flex w-full items-center justify-center" x-data="{ value: false, id: '{{ $id }}' }" x-init="$watch('value', value => $dispatch('toggle-check', {id: id, value: value}) )">
    <x-wireforms-checkbox
        wire:model="selected"
        name="selected[]"
        :show-label="false"
        :value="$id"
    />

{{--        <input--}}
{{--                type="checkbox"--}}
{{--                class="form-checkbox h-5 w-5 text-primary-500"--}}
{{--                x-model="value"--}}
{{--                @toggle-all-check.window="value = !$event.detail"--}}
{{--        >--}}
{{--    </label>--}}
</div>
