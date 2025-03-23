@extends('layouts.admin')

@section('admin')
    <main>
        <x-heading1>
            Clientes
        </x-heading1>
        <x-button-link href="{{ route('admin.new.client') }}">
            Crear nuevo cliente
        </x-button-link>
    </main>
@endsection
