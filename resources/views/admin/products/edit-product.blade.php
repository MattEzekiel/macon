@extends('layouts.admin')
@section('title', __('general.edit_product') . ': ' . $product->name)
@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        {{ __('products.edit_product') }}: <b>#{{ $product->id }}</b> - {{ $product->name }}
    </x-heading1>
    <x-stepper :steps="[__('products.edit_product'), __('products.edit_files')]" :current="0" />
    <div class="mt-3.5">
        @include('admin.products.forms.store-product', ['product' => $product])
    </div>
@endsection