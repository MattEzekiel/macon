@php use Illuminate\Support\Facades\Crypt; @endphp
@extends('layouts.admin')

@section('admin')
    @if(session('success'))
        @component('components.alert', ['variant' => 'success'])
            {{ __(session('success')) }}
        @endcomponent
    @endif
    <x-heading1>
        QR's
    </x-heading1>
    <x-table-default>
        <thead class="bg-accent-content">
        <tr>
            <th>#</th>
            <th>Cliente</th>
            <th>Producto</th>
            <th>Archivos</th>
            <th>Imagen</th>
        </tr>
        </thead>
        <tbody>
        @forelse($qrs as $qr)
            <tr>
                <td>{{ $qr->id }}</td>
                <td>{{ $qr->client->legal_name }}</td>
                <td>{{ $qr->product->name }}</td>
                <td>{{ count($qr->product->files) }}</td>
                <td>
                    <img src="{{ asset(Crypt::decrypt($qr->url_qrcode)) }}" alt="Código QR de {{ $qr->product->name }}">
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center text-2xl bg-content-200 py-2.5" colspan="100%">No hay QR's</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>

    {{ $qrs->appends(request()->query())->links() }}
@endsection
