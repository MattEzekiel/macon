@extends('layouts.admin')
@section('title', 'Nombrar archivos')
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
        Nombrar archivos
    </x-heading1>
    <div id="form" class="mt-3.5">
        @include('admin.files.forms.name-file')
    </div>
@endsection
