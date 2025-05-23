@extends('layouts.admin')
@section('title', __('users.new_user'))
@section('admin')
    @if(session('error'))
        @component('components.alert', ['variant' => 'error'])
            {{ __(session('error')) }}
        @endcomponent
    @endif
    <x-heading1>
        {{ __('users.new_user') }}
    </x-heading1>
    <div class="mt-3.5">
        @include('client.users.forms.store-user')
    </div>
@endsection