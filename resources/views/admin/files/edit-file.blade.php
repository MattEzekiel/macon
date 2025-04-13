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
    <div class="container">
        @component('components.alert', ['variant' => 'warning'])
            Antes de realizar una acción recuerde que los archivos anteriores se guardarán a no ser que los elimine
            individualmente.
        @endcomponent
    </div>
    <div id="current-files" class="mt-5 flex flex-wrap items-center justify-center gap-10 mb-10">
        @forelse($product->files as $file)
            <div class="mb-5">
                <object class="aspect-square w-full mb-3.5" data="{{ asset($file->file_url) }}"></object>
                <p><span class="text-sm">Nombre original:</span> {{ $file->original_file_name }}</p>
                <p><span class="text-sm">Nombre asignado:</span> {{ $file->file_name ?? '' }}</p>
                <button class="btn btn-xs btn-error btn-soft btn-delete-button mt-5"
                        data-id="{{'modal-' . $file->id }}">
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
                                      class="w-full grid grid-cols-1 gap-2.5 delete-button">
                                    @method('DELETE')
                                    @csrf
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
    <div id="form" class="mt-3.5">
        @include('admin.files.forms.store-file')
    </div>
@endsection
