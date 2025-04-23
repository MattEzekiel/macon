@extends('layouts.admin')
@section('title', __('general.new_file'))
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
        {{ __('products.upload_files') }}
    </x-heading1>
    <x-stepper :steps="[__('products.create_product'), __('products.upload_files'), __('products.generate_qr')]" :current="1" />
    <div class="mt-3.5">
        @include('admin.files.forms.store-file')
    </div>
@endsection
