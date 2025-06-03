<form id="{{ isset($user) ? 'update-user' : 'create-user' }}"
      action="{{ isset($user) ? route('client.user.update', ['id' => $user->id]) : route('client.user.store') }}"
      method="post"
      class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
    <p class="mb-5 mt-3 col-span-1 md:col-span-2">{{ __('users.complete_the_fields') }}</p>
    @method(isset($user) ? 'PUT' : 'POST')
    @csrf
    @foreach($form_data as $input => $value)
        @if($input !== 'client')
            <x-forms.floating-input
                    type="{{ $value }}"
                    name="{{ $input }}"
                    id="{{ $input }}"
                    label="{{ __('users.'.$input) }}"
                    placeholder="{{ __('users.'.$input) }}"
                    error="{{ $errors->has($input) ? $errors->first($input) : null }}"
                    value="{{ old($input, isset($user) && !in_array($input, ['password', 'confirm_password']) ? $user->{$input} : '') }}"
                    required={{ true }}
            />
        @endif
    @endforeach
    <div class="col-span-1 md:col-span-2 flex flex-col md:flex-row md:justify-end items-center">
        <x-forms.submit-button btn_color="btn-success">
            {{ isset($user) ? __('users.update_user') : __('users.create_new_user') }}
        </x-forms.submit-button>
    </div>
</form>