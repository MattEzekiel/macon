@props(['btn_color' => 'btn-neutral', 'class' => ''])
<button type="submit" class="btn {{ $btn_color }} {{ $class }}">
    {{ $slot }}
</button>