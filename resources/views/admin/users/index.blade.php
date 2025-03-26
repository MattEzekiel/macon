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
    <x-button-link href="{{ route('admin.new.user') }}">
        Crear nuevo usuario
    </x-button-link>
    <x-table-default>
        <thead>
        <tr>
            <th>#</th>
            <th>{{ __('users.name') }}</th>
            <th>{{ __('users.email') }}</th>
            {{--            <th>{{ __('users.role') }}</th>--}}
            <th>{{ __('users.client') }}</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                {{--                <td>{{ $user->role }}</td>--}}
                <td>{{$user->client->lega_name ?? __('users.no_client')}}</td>
                <td>
                    <x-button-link
                            href="{{ route('admin.edit.user', ['id' => $user->id]) }}"
                            class="btn-xs btn-warning btn-soft"
                    >
                        Editar
                    </x-button-link>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center text-2xl bg-content-200 py-2.5" colspan="100%">No hay usuarios</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>
    {{ $users->appends(request()->query())->links() }}
@endsection