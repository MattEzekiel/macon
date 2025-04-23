@extends('layouts.admin')
@section('title', __('users.edit_user') . ': ' . $user->name)
@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        {{ __('users.edit_user') }} : <b>#{{ $user->id }}</b> - {{ $user->name }}
    </x-heading1>
    <div class="mt-3.5">
        @include('admin.users.forms.store-user', ['user' => $user])
    </div>
@endsection