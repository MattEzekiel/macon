<form id="{{ isset($client) ? 'update-client' : 'create-client' }}"
      action="{{ isset($client) ? route('admin.client.update', ['id' => $client->id]) : route('admin.client.store') }}"
      method="post"
      class="w-full grid lg:grid-cols-2 gap-2.5">
    <p class="mb-5 mt-3 col-span-2">{{ __('clients.complete_the_fields') }}</p>
    @method(isset($client) ? 'PUT' : 'POST')
    @csrf
    @forelse($form_data as $input => $value)
        <x-forms.floating-input
                type="{{ $value }}"
                name="{{ $input }}"
                id="{{ $input }}"
                label="{{ __('clients.'.$input) }}"
                placeholder="{{ __('clients.'.$input) }}"
                error="{{ $errors->has($input) ? $errors->first($input) : null }}"
                value="{{ old($input, isset($client) ? $client->{$input} : '') }}"
                required={{ true }}
        />
    @empty
    @endforelse
    <div class="col-span-2 flex lg:justify-end items-center">
        <x-forms.submit-button btn_color="btn-success">
            {{ isset($client) ? __('general.update_client') : __('general.create_client') }}
        </x-forms.submit-button>
    </div>
</form>