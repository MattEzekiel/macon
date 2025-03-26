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
                <td>{{$user->client->legal_name ?? __('users.no_client')}}</td>
                <td>
                    <x-button-link
                            href="{{ route('admin.edit.user', ['id' => $user->id]) }}"
                            class="btn-xs btn-warning btn-soft"
                    >
                        Editar
                    </x-button-link>
                    <button class="btn btn-xs btn-error btn-soft btn-delete-button"
                            data-id="{{'modal-' . $user->id }}">
                        Eliminar
                    </button>
                    <dialog id="{{'modal-' . $user->id }}" class="modal">
                        <div class="modal-box">
                            <h3 class="text-lg font-bold">¿Desea eliminar el usuario?</h3>
                            <small class="py-2 text-xs">Presione ESC para cerrar</small>
                            <div class="modal-action mt-2.5">
                                <div class="w-full">
                                    <form id="{{ 'delete-user-' . $user->id }}"
                                          action="{{ route('admin.user.delete', ['id' => $user->id]) }}"
                                          method="post"
                                          class="w-full grid grid-cols-1 gap-2.5 delete-button">
                                        <p class="mb-5 mt-3">Escriba: <span
                                                    class="text-error">{{ $user->name }}</span> para
                                            eliminarlo</p>
                                        @method('DELETE')
                                        @csrf
                                        <x-forms.floating-input
                                                type="text"
                                                name="{{ $user->id }}_name"
                                                id="{{ $user->id }}_name"
                                                label="{{ __('users.name') }}"
                                                placeholder="{{ __('users.name') }}"
                                                required="{{ true }}"
                                        />
                                    </form>
                                    <div class="flex flex-wrap lg:justify-end items-center mt-2.5 gap-2.5">
                                        <form method="dialog">
                                            <button class="btn">Cerrar</button>
                                        </form>
                                        <button type="submit"
                                                form="{{'delete-user-' . $user->id }}"
                                                disabled
                                                class="btn btn-soft btn-error">Eliminar Usuario
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dialog>
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
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.delete-button');
            const buttons_delete = document.querySelectorAll('.btn-delete-button');
            buttons_delete.forEach(button => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.getAttribute('data-id'));
                    if (modal) {
                        modal.showModal();
                    }
                });
            });

            forms.forEach(form => {
                const input = form.querySelector('input[type="text"]');
                const client_value = form.querySelector('.text-error').textContent;
                const button = form.parentElement.querySelector('button[type="submit"]');

                input.addEventListener('input', e => {
                    const value = e.target.value;
                    if (value === client_value) {
                        button.removeAttribute('disabled');
                        button.classList.remove('btn-soft');
                    } else {
                        button.setAttribute('disabled', 'disabled');
                        button.classList.add('btn-soft');
                    }
                });
            });
        });
    </script>
@endpush