@extends('layouts.admin')
@section('title', __('general.edit_client')  . ': ' . $client->legal_name)
@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        Editar cliente: <b>#{{ $client->id }}</b> - {{ $client->legal_name }}
    </x-heading1>
    <div class="mt-3.5">
        @include('admin.clients.forms.store-client', ['client' => $client])
    </div>
@endsection