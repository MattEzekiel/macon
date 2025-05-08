@extends('layouts.general')
@section('title', $file->file_name ?: __('files.pdf_file'))
@section('general')
    <main class="flex-1 bg-gradient-to-b from-base-200 to-base-300 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-center gap-2 mb-6">
                <x-icons.file-icon class="h-6 w-6 text-base-content group-hover:text-gray-600" /> 
                <h1 class="text-2xl font-bold text-base-content text-center mb-0.5">{{ $file->file_name ?: __('files.pdf_file') }}</h1>
            </div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div id="pdf-viewer" class="w-full h-[calc(100vh-200px)] flex flex-col items-center justify-start bg-gray-100 overflow-y-auto p-4" style="max-height: calc(100vh - 200px);">
                    <canvas id="pdf-canvas" style="width: 100%; height: 100%;"></canvas>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // No permitir copiar, cortar, pegar, seleccionar, arrastrar y soltar
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            return false;
        });

        document.addEventListener('dragstart', function(e) {
            e.preventDefault();
            return false;
        });

        document.addEventListener('selectstart', function(e) {
            e.preventDefault();
            return false;
        });

        document.addEventListener('copy', function(e) {
            e.preventDefault();
            return false;
        });

        document.addEventListener('cut', function(e) {
            e.preventDefault();
            return false;
        });

        document.addEventListener('paste', function(e) {
            e.preventDefault();
            return false;
        });

        const url = "{{ route('files.get', ['id' => $file->id]) }}";
        const pdfjsLib = window['pdfjsLib'];
        if (pdfjsLib && pdfjsLib.GlobalWorkerOptions) {
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
        }

        const viewer = document.getElementById('pdf-viewer');
        viewer.innerHTML = '';

        if (!pdfjsLib || !pdfjsLib.getDocument) {
            viewer.innerHTML = '<p class="text-red-500">No se pudo inicializar el visor PDF. (pdfjsLib no está disponible)</p>';
            return;
        }

        pdfjsLib.getDocument(url).promise.then(function(pdf) {
            for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                pdf.getPage(pageNum).then(function(page) {
                    const scale = 1.5;
                    const viewport = page.getViewport({ scale: scale });
                    const canvas = document.createElement('canvas');
                    canvas.className = 'mb-8 shadow';
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    
                    canvas.setAttribute('style', '-webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;');
                    canvas.setAttribute('oncontextmenu', 'return false;');
                    canvas.setAttribute('onmousedown', 'return false;');
                    
                    viewer.appendChild(canvas);
                    const context = canvas.getContext('2d');
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    page.render(renderContext);
                });
            }
        }, function (reason) {
            viewer.innerHTML = '<p class="text-red-500">{{ __('files.pdf_error') }}</p>';
        });
    });
</script>
@endpush 