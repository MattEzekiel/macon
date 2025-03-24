@php

        @endphp
<form id="{{ isset($product) ? 'update-product' : 'create-product' }}"
      action="{{ isset($product) ? route('admin.product.update', ['id' => $product->id]) : route('admin.product.store') }}"
      method="post"
      class="w-full grid lg:grid-cols-2 gap-2.5">
    <p class="mb-5 mt-3 col-span-2">{{ __('products.complete_the_fields') }}</p>
    @method(isset($product) ? 'PUT' : 'POST')
    @csrf
    @forelse($form_data as $input => $value)
        @if($input === 'client')
            <x-forms.floating-select
                    type="{{ $value }}"
                    name="{{ $input }}"
                    id="{{ $input }}"
                    label="{{ __('products.'.$input) }}"
                    error="{{ $errors->has($input) ? $errors->first($input) : null }}"
                    value="{{ old($input, isset($product) ? $product->client_id : '') }}"
                    required="{{ true }}"
                    placeholder="Seleccione un cliente"
                    :options="$clients->map(fn($client) => json_decode(json_encode(['id' => $client->id, 'value' => $client->legal_name])))"
            />
        @elseif($value === 'select')
            if($value === 'textarea')
            <div class="col-span-2 min-h-[80px]">
                <x-forms.floating-textarea
                        type="{{ $value }}"
                        name="{{ $input }}"
                        id="{{ $input }}"
                        label="{{ __('products.'.$input) }}"
                        placeholder="{{ __('products.'.$input) }}"
                        error="{{ $errors->has($input) ? $errors->first($input) : null }}"
                        value="{{ old($input, isset($product) ? $product->{$input} : '') }}"
                        required={{ true }}
                />
            </div>
        @else
            <x-forms.floating-input
                    type="{{ $value }}"
                    name="{{ $input }}"
                    id="{{ $input }}"
                    label="{{ __('products.'.$input) }}"
                    placeholder="{{ __('products.'.$input) }}"
                    error="{{ $errors->has($input) ? $errors->first($input) : null }}"
                    value="{{ old($input, isset($product) ? $product->{$input} : '') }}"
                    required={{ true }}
            />
        @endif
    @empty
    @endforelse
    <div class="col-span-2 flex lg:justify-end items-center">
        <x-forms.submit-button btn_color="btn-success">
            {{ isset($product) ? 'Actualizar datos del producto' : 'Crear nuevo producto' }}
        </x-forms.submit-button>
    </div>
</form>