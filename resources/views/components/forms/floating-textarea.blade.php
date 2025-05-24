@props([
    'name',
    'id',
    'label',
    'placeholder' => '',
    'error' => null,
    'value' => '',
    'required' => false,
    'disabled' => false
])
<div class="w-full">
    <label
            for="{{ $id }}" class="floating-label">
        <span>{{ $label }}</span>
        <textarea
                name="{{ $name }}"
                id="{{ $id }}"
                placeholder="{{ $placeholder }}"
                class="textarea w-full {{ count($errors) > 0 ? 'textarea-error' : '' }}"
                @required($required)
                @disabled($disabled)
        >{{ $value }}</textarea>
    </label>
    <p class="validator-hint {{ $error ? 'validator-hint-error bg-error text-error-content p-1.5 rounded' : 'hidden' }}">
        {{ $error }}
    </p>
</div>
