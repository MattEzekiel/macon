@extends('layouts.admin')

@section('admin')
    <main>
        @if(session('error'))
            <x-alert type="error">{{ session('error') }}</x-alert>
        @endif
        <x-heading1>
            Nuevo cliente
        </x-heading1>
        <div class="mt-3.5">
            @include('admin.clients.forms.store-client')
        </div>
    </main>
@endsection