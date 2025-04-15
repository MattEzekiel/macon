@props([
    'name',
    'id',
    'label',
    'placeholder' => '',
    'type' => 'text',
    'error' => null,
    'value' => '',
    'required' => false,
    'disabled' => false,
    'list' => [],
    'list_id'
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
                list="{{ $list_id }}"
        />
    </label>
    <datalist id="{{ $list_id }}">
        @forelse($list as $option)
            <option value="{{ $option->id }}">{{ $option->value }}</option>
        @empty
        @endforelse
    </datalist>
    <p class="validator-hint {{ $error ? 'validator-hint-error bg-error text-error-content p-1.5 rounded' : 'hidden' }}">
        {{ $error }}
    </p>
</div>