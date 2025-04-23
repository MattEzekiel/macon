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
                    name="{{ $input }}"
                    id="{{ $input }}"
                    label="{{ __('products.'.$input) }}"
                    error="{{ $errors->has($input) ? $errors->first($input) : null }}"
                    value="{{ old($input, isset($product) ? $product->client_id : '') }}"
                    required="{{ true }}"
                    placeholder="{{ __('products.select_client') }}"
                    :options="$clients->map(fn($client) => json_decode(json_encode(['id' => $client->id, 'value' => $client->legal_name])))"
            />
        @elseif($value === 'textarea')
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
    @if(isset($product))
        <input type="hidden" name="submit_action" id="submit_action" value="">
    @endif
    <div class="col-span-2 flex lg:justify-end items-center gap-5">
        <x-forms.submit-button btn_color="btn-success" class="{{ isset($product) ? 'btn-outline' : '' }}">
            {{ isset($product) ? __('products.update_and_finish') : __('products.create_new_product') }}
        </x-forms.submit-button>
        @if(isset($product))
            <x-forms.submit-button btn_color="btn-success" value="update_and_continue">
                {{ __('products.update_and_continue') }}
            </x-forms.submit-button>
        @endif
    </div>
</form>