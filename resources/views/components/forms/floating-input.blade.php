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
<p class="validator-hint {{ count($errors) > 0 ? 'validator-hint-error' : 'hidden' }}">
    @if(count($errors) > 0)
        @foreach($errors as $error)
            {{ $error }} <br>
        @endforeach
    @endif
</p>
