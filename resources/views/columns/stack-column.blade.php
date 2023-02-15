<div @class(["relative flex gap-1", "flex-col" => !$asRow, "flex-row items-center flex-wrap" => $asRow, 'justify-center' => $toCenter])>
    @foreach($columns as $column)
        <div class="{{ $column->getClass($row) }}" wire:key="stack-{{ $id }}-{{ $name }}-{{ $loop->index }}">
            {!! $column->renderIt($row) !!}
        </div>
    @endforeach
</div>
