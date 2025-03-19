@props(['type' => ''])
@php
    $type_value = match ($type) {
        'success' => 'success',
        'error' => 'error',
        'warning' => 'warning',
        default => 'info',
    };
@endphp
@if($type !== '')
    <p role="alert" class="alert alert-soft alert-{{ $type_value }}">
        @include('components.icons.' . $type_value . '-icon')
        <span>{{ $slot }}</span>
    </p>
@endif