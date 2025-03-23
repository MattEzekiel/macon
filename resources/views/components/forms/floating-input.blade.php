@props([
    'name',
    'id',
    'label',
    'placeholder' => '',
    'type' => 'text',
    'error' => null,
    'required' => false,
])
<div>
    <label
            for="{{ $id }}" class="floating-label">
        <span>{{ $label }}</span>
        <input
                type="{{ $type }}"
                name="{{ $name }}"
                id="{{ $id }}"
                placeholder="{{ $placeholder }}"
                class="input input-md w-full {{ count($errors) > 0 ? 'input-error' : '' }}"
                @required($required)
        />
    </label>
    <p class="validator-hint {{ $error ? 'validator-hint-error bg-error text-error-content p-1.5 rounded' : 'hidden' }}">
        {{ $error }}
    </p>
</div>
