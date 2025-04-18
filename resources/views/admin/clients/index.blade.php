@extends('layouts.admin')
@section('title', __('general.clients'))
@section('admin')
    @if(session('success'))
        @component('components.alert', ['variant' => 'success'])
            {{ __(session('success')) }}
        @endcomponent
    @endif
    <x-heading1>
        Clientes
    </x-heading1>
    <x-button-link href="{{ route('admin.new.client') }}">
        Crear nuevo cliente
    </x-button-link>
    @include('admin.clients.forms.searcher')
    <x-table-default>
        <thead class="bg-accent-content">
        <tr>
            <th>#</th>
            <th>{{ __('clients.legal_name') }}</th>
            <th>{{ __('clients.tax_id') }}</th>
            <th>{{ __('clients.contact_name') }}</th>
            <th>{{ __('clients.contact_email') }}</th>
            <th>{{ __('clients.contact_phone') }}</th>
            <th>{{ __('clients.legal_address') }}</th>
            <th>{{ __('clients.products') }}</th>
            <th>{{ __('clients.files') }}</th>
            <th>{{ __('clients.qrs') }}</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        @forelse($clients as $client)
            <tr>
                <td>{{ $client->id }}</td>
                <td>{{ $client->legal_name }}</td>
                <td>{{ $client->tax_id }}</td>
                <td>{{ $client->contact_name }}</td>
                <td>{{ $client->contact_email }}</td>
                <td>{{ $client->contact_phone }}</td>
                <td>{{ $client->legal_address }}</td>
                <td>{{ $client->products()->count() }}</td>
                <td>{{ $client->files_count }}</td>
                <td>{{ $client->qrs()->count() }}</td>
                <td>
                    <x-button-link
                            href="{{ route('admin.edit.client', ['id' => $client->id]) }}"
                            class="btn-xs btn-warning btn-soft"
                    >
                        Editar
                    </x-button-link>
                    @if($client->deleted_at === null)
                        <button class="btn btn-xs btn-error btn-soft btn-delete-button"
                                data-id="{{'modal-' . $client->id }}">
                            Eliminar
                        </button>
                        <dialog id="{{'modal-' . $client->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="text-lg font-bold">¿Desea eliminar el cliente?</h3>
                                <small class="py-2 text-xs">Presione ESC para cerrar</small>
                                <div class="modal-action mt-2.5">
                                    <div class="w-full">
                                        <form id="{{ 'delete-user-' . $client->id }}"
                                              action="{{ route('admin.client.delete', ['id' => $client->id]) }}"
                                              method="post"
                                              class="w-full grid grid-cols-1 gap-2.5 delete-button">
                                            <p class="mb-5 mt-3">Escriba: <span
                                                        class="text-error">{{ $client->legal_name }}</span> para
                                                eliminarlo</p>
                                            @method('DELETE')
                                            @csrf
                                            <x-forms.floating-input
                                                    type="text"
                                                    name="{{ $client->id }}_legal_name"
                                                    id="{{ $client->id }}_legal_name"
                                                    label="{{ __('clients.legal_name') }}"
                                                    placeholder="{{ __('clients.legal_name') }}"
                                                    required="{{ true }}"
                                            />
                                        </form>
                                        <div class="flex flex-wrap lg:justify-end items-center mt-2.5 gap-2.5">
                                            <form method="dialog">
                                                <button class="btn">Cerrar</button>
                                            </form>
                                            <button type="submit"
                                                    form="{{'delete-user-' . $client->id }}"
                                                    disabled
                                                    class="btn btn-soft btn-error">Eliminar Cliente
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dialog>
                    @else
                        <form
                                action="{{ route('admin.client.restore', ['id' => $client->id]) }}"
                                method="post"
                                class="inline"
                        >
                            @csrf
                            @method('PATCH')
                            <button class="btn btn-xs btn-success btn-soft">
                                Restaurar
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center text-2xl bg-content-200 py-2.5" colspan="100%">No hay clientes</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>
    {{  $clients->appends(request()->query())->links() }}
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