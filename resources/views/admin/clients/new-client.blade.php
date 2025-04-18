@extends('layouts.admin')
@section('title', __('general.new_client'))
@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        Nuevo cliente
    </x-heading1>
    <div class="mt-3.5">
        @include('admin.clients.forms.store-client')
    </div>
@endsection