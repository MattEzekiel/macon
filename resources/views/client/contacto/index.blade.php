@extends('layouts.admin')
@section('title', __('general.contact'))
@section('admin')
    <x-heading1>
        {{ __('general.contact') }}
    </x-heading1>
    @if(session('error'))
        <div class="mb-5 md:w-1/2">
            @component('components.alert', ['variant' => 'error'])
                {{ __(session('error')) }}
            @endcomponent
        </div>
    @elseif(session('success'))
        <div class="mb-5 md:w-1/2">
            @component('components.alert', ['variant' => 'success'])
                {{ __(session('success')) }}
            @endcomponent
        </div>
    @endif
    @include('client.contacto.forms.index')
@endsection
