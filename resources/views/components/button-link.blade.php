@props(['href' => '#', 'class' => '', 'target' => '_self'])
<a href="{{ $href }}" class="btn {{  $class }}" target="{{ $target }}">
    {{ $slot }}
</a>