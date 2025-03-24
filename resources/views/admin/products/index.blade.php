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
                        {{--<button class="btn btn-xs btn-error btn-soft btn-delete-product"
                                data-id="{{'modal-' . $product->id }}">
                            Eliminar
                        </button>--}}
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

    {{  $products->appends(request()->query())->links() }}
@endsection