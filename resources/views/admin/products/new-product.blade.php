@extends('layouts.admin')
@section('title', __('products.new_product'))
@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        {{ __('products.new_product') }}
    </x-heading1>
    <x-stepper :steps="[__('products.create_product'), __('products.upload_files'), __('products.generate_qr')]" :current="0" />
    <div class="mt-3.5">
        @include('admin.products.forms.store-product')
    </div>
@endsection