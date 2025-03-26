@extends('layouts.admin')

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
        Generar QR {{ isset($product) ? "para: {$product->name}" : '' }}
    </x-heading1>
    <x-stepper :steps="['Crear producto', 'Subir archivos', 'Generar QR']" :current="2" />
    <div class="mt-3.5">
        @include('admin.qr.forms.store-qr')
    </div>
@endsection