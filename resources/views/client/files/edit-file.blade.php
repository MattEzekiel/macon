@extends('layouts.admin')
@section('title', __('files.modify_files') . ': ' . $product->name)
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
        {{ __('files.modify_files') }}
    </x-heading1>
    <div class="container mx-auto px-4">
        @component('components.alert', ['variant' => 'warning'])
            {{ __('files.file_warning') }}
        @endcomponent
    </div>
    <div class="container mx-auto px-4 mt-4 text-center">
        <a href="{{ route('client.name.files', ['id' => $product->id]) }}"
           class="btn btn-primary">
            {{ __('files.rename_files') }}
        </a>
    </div>
    <div id="current-files" class="container mx-auto px-4">
        <div class="mt-5 flex flex-wrap items-center justify-center gap-10">
            @forelse($product->files as $file)
                <div class="w-80 border rounded shadow border-gray-700 p-4 transition-all duration-300 hover:shadow-xl group">
                    <div class="relative w-full h-80">
                        <x-skeleton class="absolute inset-0 h-full w-full file-loader" />
                         @php
                            $encryptedId = Crypt::encrypt($file->id);
                        @endphp
                        <iframe
                            class="absolute inset-0 w-full h-full mb-3.5 max-w-[250px] mx-auto hidden"
                            src="{{ route('files.get', ['id' => $encryptedId]) }}#toolbar=0"
                            onload="this.classList.remove('hidden'); this.previousElementSibling.classList.add('hidden');"
                            id="file-iframe-{{$file->id}}"
                            frameborder="0"
                        ></iframe>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center space-x-2">
                            <x-icons.file-icon
                                    class="h-6 w-6 text-gray-50 group-hover:text-gray-300 transition-all duration-300" />
                            <span class="text-lg font-medium text-gray-50 transition-all duration-300 group-hover:text-gray-300">
                                {{ $file->file_name ?: $file->original_file_name }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-400">{{ __('files.original_name', ['name' => $file->original_file_name]) }}</p>
                    </div>
                    <button class="btn btn-xs btn-error btn-soft mt-4 mx-auto block transition-colors duration-300 btn-delete-button"
                            data-id="modal-{{ $file->id }}">
                        {{ __('general.delete') }}
                    </button>
                    <dialog id="modal-{{ $file->id }}" class="modal">
                        <div class="modal-box">
                            <h3 class="text-lg font-bold">{{ __('files.delete_file_confirmation') }}</h3>
                            <small class="py-2 text-xs">{{ __('general.press_esc') }}</small>
                            <div class="modal-action mt-2.5">
                                <div class="w-full">
                                    <form id="delete-file-{{ $file->id }}"
                                          action="{{ route('client.file.delete', ['id' => $file->id]) }}"
                                          method="post"
                                          class="w-full grid grid-cols-1 gap-2.5 delete-button">
                                        <p class="mb-5 mt-3">{!! __('files.type_to_delete', ['name' => $file->file_name ?: $file->original_file_name]) !!}</p>
                                        @method('DELETE')
                                        @csrf
                                        <input type="hidden" class="file-name-to-delete"
                                               value="{{ $file->file_name ?: $file->original_file_name }}">
                                        <x-forms.floating-input
                                                type="text"
                                                name="{{ $file->id }}_name"
                                                id="{{ $file->id }}_name"
                                                label="{{ __('files.file_name') }}"
                                                placeholder="{{ __('files.file_name') }}"
                                                required="true"
                                        />
                                    </form>
                                    <div class="flex flex-wrap lg:justify-end items-center mt-2.5 gap-2.5">
                                        <form method="dialog">
                                            <button class="btn">{{ __('general.close') }}</button>
                                        </form>
                                        <button type="submit"
                                                form="delete-file-{{ $file->id }}"
                                                disabled
                                                class="btn btn-soft btn-error">{{ __('files.delete_file') }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </dialog>
                </div>
            @empty
                <p class="w-full text-center text-lg">{{ __('files.no_files') }}</p>
            @endforelse
        </div>
    </div>
    <div id="form" class="mt-3.5">
        @include('client.files.forms.store-file', ['product' => $product])
    </div>
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
                const file_name = form.querySelector('.file-name-to-delete').value;
                const button = form.parentElement.querySelector('button[type="submit"]');

                input.addEventListener('input', e => {
                    const value = e.target.value;
                    if (value === file_name) {
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
    <script>
        // La función hideLoader ya no es necesaria para los iframes
    </script>
@endpush 