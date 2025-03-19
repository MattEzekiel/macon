@props([
    'name',
    'id',
    'label',
    'placeholder' => '',
    'type' => 'text',
    'errors' => [],
    'required' => false
])

<label
    for="{{ $id }}" class="q-floating-label">
    <span>{{ $label }}</span>
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $id }}"
        placeholder="{{ $placeholder }}"
        class="q-input q-input-md {{ count($errors) > 0 ? 'q-input-error' : '' }}"
        @required($required)
    />
</label>
<p class="validator-hint {{ count($errors) > 0 ? 'validator-hint-error' : 'hidden' }}">
    @if(count($errors) > 0)
        @foreach($errors as $error)
            {{ $error }} <br>
        @endforeach
    @endif
</p>
