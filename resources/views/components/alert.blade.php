@props(['variant' => ''])
@php
    $type_value = match ($variant) {
        'success' => 'alert-success',
        'error' => 'alert-error',
        'warning' => 'alert-warning',
        default => 'alert-info',
    };
@endphp
@if($variant)
    <div role="alert" class="alert {{ $type_value }} alert-soft">
        @include('components.icons.' . $variant . '-icon')
        <span>{{ $slot }}</span>
    </div>
@endif