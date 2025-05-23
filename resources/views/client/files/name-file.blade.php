@extends('layouts.admin')
@section('title', __('files.name_files'))
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
        {{ __('files.rename_files') }}
    </x-heading1>
    <div id="form" class="mt-3.5">
        @include('client.files.forms.name-file', ['product' => $product, 'files' => $files])
    </div>
@endsection 