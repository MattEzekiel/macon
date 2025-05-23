@php use Illuminate\Support\Facades\Crypt; @endphp
@extends('layouts.general')
@section('title', $file->file_name ?: __('files.pdf_file'))
@section('general')
    <main class="flex-1 bg-gradient-to-b from-base-200 to-base-300 p-6">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-center gap-2 mb-6">
                <x-icons.file-icon class="h-6 w-6 text-base-content group-hover:text-gray-600" />
                <h1 class="text-2xl font-bold text-base-content text-center mb-0.5">{{ $file->file_name ?: __('files.pdf_file') }}</h1>
            </div>
            @php
                $encryptedId = Crypt::encrypt($file->id);
            @endphp
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div id="pdf-viewer"
                     class="w-full h-[calc(100vh-200px)] flex flex-col items-center justify-start bg-gray-100 overflow-y-auto p-4">
                    <iframe
                            src="{{ route('files.get', ['id' => $encryptedId]) }}#toolbar=0"
                            width="100%"
                            height="100%"
                            style="border: none;"
                    >
                    </iframe>
                </div>
            </div>
        </div>
    </main>
@endsection
{{--
@push('scripts')
@vite('resources/js/app.js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        ['contextmenu', 'dragstart', 'selectstart', 'copy', 'cut', 'paste'].forEach(event => {
            document.addEventListener(event, function(e) {
                e.preventDefault();
                return false;
            });
        });

        const url = "{{ route('files.get', ['id' => $file->id]) }}";
        const viewer = document.getElementById('pdf-viewer');
        viewer.innerHTML = '';

        async function loadPDF() {
            try {
                const pdf = await window.pdfjsLib.getDocument(url).promise;
                
                for (let pageNum = 1; pageNum <= pdf.numPages; pageNum++) {
                    const page = await pdf.getPage(pageNum);
                    const scale = 1.5;
                    const viewport = page.getViewport({ scale: scale });
                    const canvas = document.createElement('canvas');
                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    canvas.className = 'mb-8 shadow bg-white rounded';

                    const context = canvas.getContext('2d');
                    const renderContext = {
                        canvasContext: context,
                        viewport: viewport
                    };
                    
                    await page.render(renderContext).promise;
                    viewer.appendChild(canvas);
                }
            } catch (error) {
                console.error('Error loading PDF:', error);
            }
        }

        loadPDF();
    });
</script>
@endpush --}}
