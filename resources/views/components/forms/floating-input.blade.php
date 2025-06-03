@props([
    'name',
    'id',
    'label',
    'placeholder' => '',
    'type' => 'text',
    'error' => null,
    'value' => '',
    'required' => false,
    'disabled' => false
])
<div class="w-full">
    <label
            for="{{ $id }}" class="floating-label">
        <span>{{ $label }}</span>
        <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $id }}"
                placeholder="{{ $placeholder }}"
                class="input input-md w-full {{ count($errors) > 0 ? 'input-error' : '' }}"
                value="{{ $value }}"
                @required($required)
                @disabled($disabled)
        />
    </label>
    <p class="validator-hint {{ $error ? 'validator-hint-error bg-error text-error-content p-1.5 rounded visible' : 'hidden' }}">
        {{ $error }}
    </p>
</div>
