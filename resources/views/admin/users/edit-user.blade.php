@extends('layouts.admin')

@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        Editar usuario: <b>#{{ $user->id }}</b> - {{ $user->name }}
    </x-heading1>
    <div class="mt-3.5">
        @include('admin.products.forms.store-product', ['product' => $user])
    </div>
@endsection