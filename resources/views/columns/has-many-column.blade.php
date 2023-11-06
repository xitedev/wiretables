<div class="flex flex-wrap gap-1.5 items-center">
    @foreach($data->take($limit) as $key => $value)
        @if($showRoute)
            <a @class(["px-1 py-0.5 rounded text-xs", $color]) href="{{ route($showRoute, $key) }}">
                {{ $value }}
            </a>
        @else
            <span
                @class(["px-1 py-0.5 rounded text-xs", $color, "cursor-pointer" => !is_null($filter)])
                @if(!is_null($filter))@click.prevent="$wire.addFilterOutside('{{ $filter }}', {{ $key }})" @endif
            >
                {{ $value }}
            </span>
        @endif
    @endforeach

    @if(count($data) > $limit)
        <a
            @class([
                "px-1 py-0.5 rounded bg-primary-200 text-primary-700 hover:bg-primary-300 hover:text-primary-700 text-xs focus:outline-none"
            ])
            @if($showMoreRoute)
                href="{{ $showMoreRoute }}"
                target="_blank"
            @endif
        >
            + {{ count($data) - $limit }}
        </a>
    @endif
</div>
