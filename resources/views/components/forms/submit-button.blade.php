@props(['btn_color' => 'btn-neutral', 'class' => '', 'value' => null])
<button
        type="submit"
        class="btn {{ $btn_color }} {{ $class }}"
        @if(!is_null($value))
            name="submit_action"
        value="{{ $value }}"
        @endif
>
    {{ $slot }}
</button>