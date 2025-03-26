<form id="{{ isset($user) ? 'update-user' : 'create-user' }}"
      action="{{ isset($user) ? route('admin.users.update', ['id' => $user->id]) : route('admin.user.store') }}"
      method="post"
      class="w-full grid lg:grid-cols-2 gap-2.5">
    <p class="mb-5 mt-3 col-span-2">{{ __('users.complete_the_fields') }}</p>
    @method(isset($user) ? 'PUT' : 'POST')
    @csrf
    @forelse($form_data as $input => $value)
        @if($value === 'select')
            <x-forms.floating-select
                    type="{{ $value }}"
                    name="{{ $input }}"
                    id="{{ $input }}"
                    label="{{ __('users.'.$input) }}"
                    error="{{ $errors->has($input) ? $errors->first($input) : null }}"
                    value="{{ old($input, isset($user) ? $user->client_id : '') }}"
                    required="{{ true }}"
                    placeholder="Seleccione un cliente"
                    :options="$clients->map(fn($client) => json_decode(json_encode(['id' => $client->id, 'value' => $client->legal_name])))"
            />
        @else
            <x-forms.floating-input
                    type="{{ $value }}"
                    name="{{ $input }}"
                    id="{{ $input }}"
                    label="{{ __('users.'.$input) }}"
                    placeholder="{{ __('users.'.$input) }}"
                    error="{{ $errors->has($input) ? $errors->first($input) : null }}"
                    value="{{ old($input, isset($user) ? $user->{$input} : '') }}"
                    required={{ true }}
            />
        @endif
    @empty
    @endforelse
    <div class="col-span-2 flex lg:justify-end items-center">
        <x-forms.submit-button btn_color="btn-success">
            {{ isset($user) ? 'Actualizar datos del usuario' : 'Crear nuevo usuario' }}
        </x-forms.submit-button>
    </div>
</form>