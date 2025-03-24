@extends('layouts.admin')
@section('admin')
    @if(session('success'))
        @component('components.alert', ['variant' => 'success'])
            {{ __(session('success')) }}
        @endcomponent
    @endif
    <x-heading1>
        Productos
    </x-heading1>
    <x-button-link href="{{ route('admin.new.product') }}">
        Crear nuevo producto
    </x-button-link>
    <div class="mt-3.5 overflow-x-auto">
        <table class="table table-xs table-pin-rows table-pin-cols">
            <thead class="bg-accent-content">
            <tr>
                <th>#</th>
                <th>{{ __('products.client') }}</th>
                <th>{{ __('products.name') }}</th>
                <th>{{ __('products.description') }}</th>
                <th>{{ __('products.brand') }}</th>
                <th>{{ __('products.model') }}</th>
                <th>{{ __('products.origin') }}</th>
                <th>Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->client->legal_name }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->brand }}</td>
                    <td>{{ $product->model }}</td>
                    <td>{{ $product->origin }}</td>
                    <td>
                        <x-button-link
                                href="{{ route('admin.edit.product', ['id' => $product->id]) }}"
                                class="btn-xs btn-warning btn-soft"
                        >
                            Editar
                        </x-button-link>
                        <button class="btn btn-xs btn-error btn-soft btn-delete-client"
                                data-id="{{'modal-' . $product->id }}">
                            Eliminar
                        </button>
                        <dialog id="{{'modal-' . $product->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="text-lg font-bold">¿Desea eliminar el producto?</h3>
                                <small class="py-2 text-xs">Presione ESC para cerrar</small>
                                <div class="modal-action mt-2.5">
                                    <div class="w-full">
                                        <form id="{{ 'delete-product-' . $product->id }}"
                                              action="{{ route('admin.product.delete', ['id' => $product->id]) }}"
                                              method="post"
                                              class="w-full grid grid-cols-1 gap-2.5 delete-client">
                                            <p class="mb-5 mt-3">Escriba: <span
                                                        class="text-error">{{ $product->name }}</span> para
                                                eliminarlo</p>
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
                                                <button class="btn">Cerrar</button>
                                            </form>
                                            <button type="submit"
                                                    form="{{'delete-product-' . $product->id }}"
                                                    disabled
                                                    class="btn btn-soft btn-error">Eliminar producto
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dialog>
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center text-2xl bg-content-200 py-2.5" colspan="100%">No hay productos</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $products->appends(request()->query())->links() }}
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.delete-client');
            const buttons_delete = document.querySelectorAll('.btn-delete-client');
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