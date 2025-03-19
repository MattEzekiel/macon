@props(['name', 'id', 'label', 'placeholder' => '', 'type' => 'text'])

<label for="{{ $id }}" class="q-floating-label">
    <span>{{ $label }}</span>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        class="q-input q-input-md"
    />
</label>
