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
        Modificar archivos
    </x-heading1>
    <div class="container mx-auto px-4">
        @component('components.alert', ['variant' => 'warning'])
            Antes de realizar una acción recuerde que los archivos anteriores se guardarán a no ser que los elimine
            individualmente.
        @endcomponent
    </div>
    <div class="container mx-auto px-4 mt-4 text-center">
        <a href="{{ route('admin.name.files', ['id' => $product->id]) }}" 
           class="btn btn-primary">
            Renombrar archivos
        </a>
    </div>
    <div id="current-files" class="container mx-auto px-4">
        <div class="mt-5 flex flex-wrap items-center justify-center gap-10">
            @forelse($product->files as $file)
                <div class="w-80 border rounded shadow border-gray-700 p-4 transition-all duration-300 hover:shadow-xl group" id="file-container-{{ $file->id }}">
                    <object class="aspect-square w-full mb-3.5 max-w-[250px] mx-auto" data="{{ asset($file->file_url) }}"></object>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <x-icons.file-icon class="h-6 w-6 text-gray-50 group-hover:text-gray-300 transition-all duration-300" />
                            <span class="text-lg font-medium text-gray-50 transition-all duration-300 group-hover:text-gray-300">
                                {{ $file->file_name ?: $file->original_file_name }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-400">Nombre original: {{ $file->original_file_name }}</p>
                    </div>
                    <button class="btn btn-xs btn-error btn-soft mt-4 mx-auto block transition-colors duration-300 btn-delete-button"
                            onclick="document.getElementById('modal-{{ $file->id }}').showModal()">
                        Eliminar
                    </button>
                    <dialog id="{{'modal-' . $file->id }}" class="modal">
                        <div class="modal-box">
                            <h3 class="text-lg font-bold">¿Está seguro que desea eliminar este archivo?</h3>
                            <small class="py-2 text-xs">Presione ESC para cerrar</small>
                            <div class="modal-action mt-2.5">
                                <div class="w-full">
                                    <form id="{{ 'delete-file-' . $file->id }}"
                                          action="{{ route('admin.file.delete', ['id' => $file->id]) }}"
                                          method="post"
                                          class="w-full grid grid-cols-1 gap-2.5">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <div class="flex flex-wrap lg:justify-end items-center mt-2.5 gap-2.5">
                                        <form method="dialog">
                                            <button class="btn">Cerrar</button>
                                        </form>
                                        <button type="submit"
                                                form="{{'delete-file-' . $file->id }}"
                                                class="btn btn-soft btn-error">Eliminar archivo
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dialog>
                </div>
            @empty
                <p class="w-full text-center text-lg">No hay archivos para mostrar</p>
            @endforelse
        </div>
    </div>
    <div id="form" class="mt-3.5">
        @include('admin.files.forms.store-file')
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cerrar modales con ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('dialog[open]');
            modals.forEach(modal => modal.close());
        }
    });

    // Manejar envío de formularios
    const deleteForms = document.querySelectorAll('form[id^="delete-file-"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const fileId = form.id.replace('delete-file-', '');
            const modal = document.getElementById(`modal-${fileId}`);
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => Promise.reject(err));
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Cerrar el modal
                    modal.close();
                    
                    // Eliminar el contenedor del archivo
                    const fileContainer = document.getElementById(`file-container-${fileId}`);
                    if (fileContainer) {
                        fileContainer.remove();
                    }
                    
                    // Mostrar mensaje de éxito
                    alert(data.message);
                    
                    // Si no quedan archivos, mostrar mensaje
                    const remainingFiles = document.querySelectorAll('[id^="file-container-"]');
                    if (remainingFiles.length === 0) {
                        const filesContainer = document.querySelector('.mt-5.flex');
                        filesContainer.innerHTML = '<p class="w-full text-center text-lg">No hay archivos para mostrar</p>';
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Hubo un error al eliminar el archivo');
                modal.close();
            });
        });
    });
});
</script>
@endpush

