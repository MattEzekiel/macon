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
        Subir archivos
    </x-heading1>
    <x-stepper :steps="['Crear producto', 'Subir archivos', 'Generar QR']" :current="1" />
    <div class="mt-3.5">
        @include('admin.files.forms.store-file')
    </div>
@endsection
@push('scripts')
    <script>
        function displayFiles(e) {
            removePreviewsFiles();
            const files = e.target.files;
            const form = document.querySelector('form');
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const reader = new FileReader();
                if (!document.querySelector('#preview-pdf')) {
                    const div = document.createElement('div');
                    div.setAttribute('id', 'preview-pdf');
                    div.className = 'mt-5 flex flex-wrap items-center justify-center gap-10';
                    form.insertAdjacentElement('afterend', div);
                }
                reader.onload = function(e) {
                    const pdf = document.createElement('object');
                    pdf.data = e.target.result;
                    pdf.type = 'application/pdf';
                    document.querySelector('#preview-pdf').appendChild(pdf);
                };
                reader.readAsDataURL(file);
            }
        }

        function removePreviewsFiles() {
            const pdf_container = document.querySelector('#preview-pdf');
            if (pdf_container) {
                pdf_container.remove();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const input_file = document.getElementById('files');
            input_file.addEventListener('change', displayFiles);
        });
    </script>
@endpush
