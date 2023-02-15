<div class="flex items-center space-x-1">
    @if($data->color())
        <div class="rounded-full h-3.5 w-3.5 border-[3px] border-gray-500"
             style="border-color: {{ $data->color() }} !important;"
             @if($popover || !$showText) x-tooltip.raw="{{ $popover ?? $data->title() }}" @endif
        ></div>
    @endif

    @if($showText)
        <span class="group inline-flex items-center space-x-1 truncate text-sm  font-medium leading-5 text-gray-700" @if($paintOverText) style="color: {{ $data->color() }} !important;" @endif>
        {{ $data->title() }}
    </span>
    @endif
</div>
