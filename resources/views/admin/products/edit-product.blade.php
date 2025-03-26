@extends('layouts.admin')

@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        Editar producto: <b>#{{ $product->id }}</b> - {{ $product->name }}
    </x-heading1>
    <x-stepper :steps="['Editar producto', 'Modificar archivos']" :current="0" />
    <div class="mt-3.5">
        @include('admin.products.forms.store-product', ['product' => $product])
    </div>
@endsection