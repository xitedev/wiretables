<div class="flex items-center space-x-1">
    <div @class(["rounded-full h-3.5 w-3.5 border-[3px]", $data->borderColor()])
         @if($popover || !$showText) x-tooltip.raw="{{ $popover ?? $data->title() }}" @endif
    ></div>

    @if($showText)
        <span @class(["group inline-flex items-center space-x-1 truncate text-sm font-medium leading-5 text-gray-700", $data->color() => $paintOverText])>
        {{ $data->title() }}
    </span>
    @endif
</div>
