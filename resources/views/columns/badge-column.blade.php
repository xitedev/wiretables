<span
    @class([
        "px-1 py-0.5 rounded leading-normal",
        "text-sm" => !$small,
        "text-xs" => $small,
        $color
    ])
    @if($popover) x-tooltip.raw="{{ $popover }}" @endif
>
    {{ $data }}
</span>
