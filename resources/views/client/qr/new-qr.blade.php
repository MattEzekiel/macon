@extends('layouts.admin')
@section('title', __('general.new_qr'))
@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @elseif(session('success'))
        @component('components.alert', ['variant' => 'success'])
            {{ __(session('success')) }}
        @endcomponent
    @endif
    <x-heading1>
        {{ __('qrs.generate_qr') }} {{ isset($product) ? __('general.for') . ": {$product->name}" : '' }}
    </x-heading1>
    <x-stepper :steps="[__('products.create_product'), __('products.upload_files'), __('products.generate_qr')]" :current="2" />
    <div class="mt-3.5">
        @include('client.qr.forms.store-qr')
    </div>
@endsection