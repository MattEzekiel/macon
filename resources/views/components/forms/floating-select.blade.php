@props([
    'name',
    'id',
    'label',
    'error' => null,
    'value' => '',
    'required' => false,
    'disabled' => false,
    'options' => [],
    'placeholder' => __('general.select_option')
])
<div class="w-full">
    <label
            for="{{ $id }}" class="floating-label">
        <span>{{ $label }}</span>
        <select
                name="{{ $name }}"
                id="{{ $id }}"
                class="input input-md w-full {{ count($errors) > 0 ? 'input-error' : '' }}"
                @required($required)
                @disabled($disabled)
        >
            @if(count($options) > 0)
                @if(!$value)
                    <option selected disabled>{{ $placeholder }}</option>
                @endif
                @foreach($options as $option)
                    <option @selected($value == $option->id) value="{{ $option->id }}">{{ $option->value }}</option>
                @endforeach
            @else
                <option selected disabled>{{ __('general.no_options') }}</option>
            @endif
        </select>
    </label>
    <p class="validator-hint {{ $error ? 'validator-hint-error bg-error text-error-content p-1.5 rounded' : 'hidden' }}">
        {{ $error }}
    </p>
</div>
