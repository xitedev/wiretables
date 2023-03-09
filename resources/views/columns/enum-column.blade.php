<div class="flex justify-center">
    @if($data)
        <span
            @class([
                "inline-flex items-center px-1.5 rounded-sm font-medium text-xs leading-5 bg-gradient-to-r from-primary-100 to-primary-300 text-primary-700 whitespace-nowrap",
                $data->color() => method_exists($data, 'color')
            ])
        >
            {{ method_exists($data, 'label') ? $data->label() : $data }}
        </span>
    @endif
</div>
