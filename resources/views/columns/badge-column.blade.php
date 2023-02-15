<span
    @class([
        "px-1 py-0.5 rounded leading-normal",
        "text-xs" => !$small,
        "text-xxs" => $small,
        $color
    ])
    @if($popover) x-tooltip.raw="{{ $popover }}" @endif
>
    {{ $data }}
</span>
