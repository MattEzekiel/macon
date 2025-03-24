@extends('layouts.admin')
@section('admin')
    @if(session('success'))
        @component('components.alert', ['variant' => 'success'])
            {{ __(session('success')) }}
        @endcomponent
    @endif
    <x-heading1>
        Usuarios
    </x-heading1>
    {{ $users->appends(request()->query())->links() }}
@endsection