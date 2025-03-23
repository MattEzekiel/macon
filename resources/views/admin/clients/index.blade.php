@extends('layouts.admin')

@section('admin')
    <main>
        <x-heading1>
            Clientes
        </x-heading1>
        <x-button-link href="{{ route('admin.new.client') }}">
            Crear nuevo cliente
        </x-button-link>
        <div class="mt-3.5 overflow-x-auto">
            <table class="table table-xs table-pin-rows table-pin-cols">
                <thead class="bg-accent-content">
                <tr>
                    <th>#</th>
                    <th>{{ __('clients.legal_name') }}</th>
                    <th>{{ __('clients.tax_id') }}</th>
                    <th>{{ __('clients.contact_name') }}</th>
                    <th>{{ __('clients.contact_email') }}</th>
                    <th>{{ __('clients.contact_phone') }}</th>
                    <th>{{ __('clients.legal_address') }}</th>
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
                        <td>
                            <x-button-link
                                    href="{{ route('admin.edit.client', ['id' => $client->id]) }}"
                                    class="btn-xs btn-warning btn-soft"
                            >
                                Editar
                            </x-button-link>
                            {{--<x-button-link href="{{ route('admin.client.delete', $client->id) }}" class="btn-xs">
                                Eliminar
                            </x-button-link>--}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-accent text-center text-2xl" colspan="100%">No hay clientes</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </main>
@endsection
