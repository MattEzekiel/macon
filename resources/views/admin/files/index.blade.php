@extends('layouts.admin')

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
        Archivos
    </x-heading1>

    <x-table-default>
        <thead class="bg-accent-content">
            <tr>
                <th>#</th>
                <th>Nombre del archivo</th>
                <th>Nombre original</th>
                <th>Producto</th>
                <th>Cliente</th>
                <th>Tamaño</th>
                <th>Fecha de creación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($files as $file)
                <tr>
                    <td>{{ $file->id }}</td>
                    <td>{{ $file->file_name ?: $file->original_file_name }}</td>
                    <td>{{ $file->original_file_name }}</td>
                    <td>{{ $file->product->name }}</td>
                    <td>{{ $file->product->client->legal_name }}</td>
                    <td>{{ $file->formatFileSize($file->file_size) }}</td>
                    <td>{{ $file->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <a href="{{ asset($file->file_url) }}" 
                               target="_blank"
                               class="btn btn-xs btn-primary btn-soft">
                                Ver archivo
                            </a>
                            <a href="{{ route('admin.edit.files', ['id' => $file->product_id]) }}"
                               class="btn btn-xs btn-warning btn-soft">
                                Editar
                            </a>
                        </div>
                    </td>
                </tr>
                
            @empty
                <tr>
                    <td colspan="8" class="text-center text-2xl bg-content-200 py-2.5">No hay archivos para mostrar</td>
                </tr>
            @endforelse
        </tbody>
    </x-table-default>

    <div class="mt-4">
        {{ $files->links() }}
    </div>
@endsection 