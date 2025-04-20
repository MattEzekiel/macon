@extends('layouts.admin')
@section('title', __('general.new_product'))
@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        Nuevo producto
    </x-heading1>
    <x-stepper :steps="['Crear producto', 'Subir archivos', 'Generar QR']" :current="0" />
    <div class="mt-3.5">
        @include('admin.products.forms.store-product')
    </div>
@endsection