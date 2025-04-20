@extends('layouts.admin')
@section('title', 'Nuevo Archivo')
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
        Subir archivos
    </x-heading1>
    <x-stepper :steps="['Crear producto', 'Subir archivos', 'Generar QR']" :current="1" />
    <div class="mt-3.5">
        @include('admin.files.forms.store-file')
    </div>
@endsection
