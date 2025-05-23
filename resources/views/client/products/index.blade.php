@extends('layouts.admin')
@section('title', __('general.products'))
@section('admin')
    @if(session('success'))
        @component('components.alert', ['variant' => 'success'])
            {{ __(session('success')) }}
        @endcomponent
    @endif
    <x-heading1>
        {{ __('general.products') }}
    </x-heading1>
    <x-button-link href="{{ route('client.new.product') }}" class="btn-success">
        {{ __('general.new_product') }}
    </x-button-link>
    @include('client.products.forms.searcher')
    <x-table-default>
        <thead class="bg-accent-content">
        <tr>
            <th>#</th>
            {{-- Columna Cliente eliminada --}}
            <th>{{ __('products.name') }}</th>
            <th>{{ __('products.description') }}</th>
            <th>{{ __('products.brand') }}</th>
            <th>{{ __('products.model') }}</th>
            <th>{{ __('products.origin') }}</th>
            <th>{{ __('products.files') }}</th>
            <th>{{ __('general.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($products as $product)
            <tr>
                <td data-label="#">{{ $product->id }}</td>
                {{-- Columna Cliente eliminada --}}
                <td data-label="{{ __('products.name') }}">{{ $product->name }}</td>
                <td data-label="{{ __('products.description') }}">{{ $product->description }}</td>
                <td data-label="{{ __('products.brand') }}">{{ $product->brand }}</td>
                <td data-label="{{ __('products.model') }}">{{ $product->model }}</td>
                <td data-label="{{ __('products.origin') }}">{{ $product->origin }}</td>
                <td data-label="{{ __('products.files') }}">
                    <span class="tooltip" data-tip="{{ __('files.view_files') }}">
                        <a href="{{ route('client.files', ['product' => $product->id]) }}"
                           class="badge text-xs badge-primary gap-1 cursor-pointer hover:brightness-90">
                            {{ $product->files->count() }}
                            <x-icons.external-link class="h-4 w-4 text-white" />
                        </a>
                    </span>
                </td>
                <td data-label="{{ __('general.actions') }}">
                <span class="flex flex-col sm:flex-row sm:gap-x-1 items-center gap-y-2.5">
                    <x-button-link
                            href="{{ route('client.edit.product', ['id' => $product->id]) }}"
                            class="btn-xs btn-warning btn-soft max-sm:w-full"
                    >
                        {{ __('general.edit') }}
                    </x-button-link>
                    @if($product->deleted_at === null)
                        <button class="btn btn-xs btn-error btn-soft btn-delete-button max-sm:w-full"
                                data-id="{{'modal-' . $product->id }}">
                            {{ __('general.delete') }}
                        </button>
                        <dialog id="{{'modal-' . $product->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="text-lg font-bold">{{ __('products.confirm_delete') }}</h3>
                                <small class="py-2 text-xs">{{ __('general.press_esc') }}</small>
                                <div class="modal-action mt-2.5">
                                    <div class="w-full">
                                        <form id="{{ 'delete-product-' . $product->id }}"
                                              action="{{ route('client.product.delete', ['id' => $product->id]) }}"
                                              method="post"
                                              class="w-full grid grid-cols-1 gap-2.5 delete-button">
                                            <p class="mb-5 mt-3">{!! __('products.type_name_to_delete', ['name' => $product->name]) !!}</p>
                                            @method('DELETE')
                                            @csrf
                                            <x-forms.floating-input
                                                    type="text"
                                                    name="{{ $product->id }}_name"
                                                    id="{{ $product->id }}_name"
                                                    label="{{ __('products.name') }}"
                                                    placeholder="{{ __('products.name') }}"
                                                    required="{{ true }}"
                                            />
                                        </form>
                                        <div class="flex flex-wrap lg:justify-end items-center mt-2.5 gap-2.5">
                                            <form method="dialog">
                                                <button class="btn">{{ __('general.close') }}</button>
                                            </form>
                                            <button type="submit"
                                                    form="{{'delete-product-' . $product->id }}"
                                                    disabled
                                                    class="btn btn-soft btn-error">{{ __('products.delete_product') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dialog>
                    @else
                        <form
                                action="{{ route('client.product.restore', ['id' => $product->id]) }}"
                                method="post"
                                class="inline"
                        >
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-xs btn-success btn-soft">
                                {{ __('general.restore') }}
                            </button>
                        </form>
                    @endif
                </span>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center text-2xl bg-content-200 py-2.5"
                    colspan="100%">{{ __('products.no_products') }}</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>

    {{ $products->appends(request()->query())->links() }}
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.delete-button');
            const buttons_delete = document.querySelectorAll('.btn-delete-button');

            buttons_delete.forEach(button => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.getAttribute('data-id'));
                    if (modal) {
                        modal.showModal();
                    }
                });
            });

            forms.forEach(form => {
                const input = form.querySelector('input[type="text"]');
                const client_value = form.querySelector('.text-error').textContent;
                const button = form.parentElement.querySelector('button[type="submit"]');
                input.addEventListener('input', e => {
                    const value = e.target.value;
                    if (value === client_value) {
                        button.removeAttribute('disabled');
                        button.classList.remove('btn-soft');
                    } else {
                        button.setAttribute('disabled', 'disabled');
                        button.classList.add('btn-soft');
                    }
                });
            });
        });
    </script>
@endpush

</rewritten_file>