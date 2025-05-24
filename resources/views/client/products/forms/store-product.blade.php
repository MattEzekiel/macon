<form id="{{ isset($product) ? 'update-product' : 'create-product' }}"
      action="{{ isset($product) ? route('client.product.update', ['id' => $product->id]) : route('client.product.store') }}"
      method="post"
      class="w-full grid grid-cols-1 md:grid-cols-2 gap-4">
    <p class="mb-5 mt-3 col-span-1 md:col-span-2">{{ __('products.complete_the_fields') }}</p>
    @method(isset($product) ? 'PUT' : 'POST')
    @csrf
    @forelse($form_data as $input => $value)
        @if($input === 'client')
            {{-- Campo cliente eliminado para el formulario de cliente --}}
        @elseif($value === 'textarea')
            <div class="col-span-1 md:col-span-2 min-h-[80px]">
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
        <input type="hidden" name="submit_action" id="submit_action_hidden" value="">
    @endif
    <div class="col-span-1 md:col-span-2 flex flex-col md:flex-row md:justify-end items-center gap-5">
        <x-forms.submit-button name="submit_action" value="update_and_finish" class="{{ isset($product) ? 'btn-success btn-outline' : '' }}" onclick="document.getElementById('submit_action_hidden').value='update_and_finish'">
            {{ isset($product) ? __('products.update_and_finish') : __('products.create_new_product') }}
        </x-forms.submit-button>
        @if(isset($product))
            <x-forms.submit-button name="submit_action" value="update_and_continue" btn_color="btn-success" onclick="document.getElementById('submit_action_hidden').value='update_and_continue'">
                {{ __('products.update_and_continue') }}
            </x-forms.submit-button>
        @endif
    </div>
</form> 