<a
    @class([
        "inline-flex items-center px-2 py-0.5 rounded text-xs",
        $color,
        "hover:opacity-90 font-semibold focus:outline-none" => !is_null($route)
    ])
    @if(!is_null($route)) href="{{ $route }}" target="_blank" @endif
    @if(!is_null($popover)) x-tooltip.raw="{{ $popover }}" @endif
>
    {{ $data }}
</a>
