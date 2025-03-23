<form id="create-user" action="{{ route('admin.client.store') }}" method="post"
      class="w-full grid lg:grid-cols-2 gap-2.5">
    <p class="mb-5 mt-3 col-span-2">{{ __('clients.complete_the_fields') }}</p>
    @csrf
    @forelse($form_data as $input => $value)
        <x-forms.floating-input
                type="{{ $value }}"
                name="{{ $input }}"
                id="{{ $input }}"
                label="{{ __('clients.'.$input) }}"
                placeholder="{{ __('clients.'.$input) }}"
                error="{{ $errors->has($input) ? $errors->first($input) : null }}"
                {{--                required={{ true }}--}}
        />
    @empty
    @endforelse
    <div class="col-span-2 flex lg:justify-end items-center">
        <x-forms.submit-button btn_color="btn-success">
            Crear nuevo cliente
        </x-forms.submit-button>
    </div>
</form>