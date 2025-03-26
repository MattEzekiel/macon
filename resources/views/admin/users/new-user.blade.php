@extends('layouts.admin')

@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        Nuevo usuario
    </x-heading1>
    <div class="mt-3.5">
        @include('admin.users.forms.store-user')
    </div>
@endsection