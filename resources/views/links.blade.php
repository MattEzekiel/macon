@extends('layouts.general')
@section('title', isset($product) ? $product->name : __('general.files'))
@section('general')
    <main class="flex-1 bg-gradient-to-b from-base-200 to-base-300 p-6">
        <div class="max-w-2xl mx-auto">
            @if(isset($product))
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold mb-2 text-base-content">{{ $product->name }}</h1>
                    <p class="text-xl text-base-content/80">{{ $product->client->legal_name }}</p>
                </div>
            @endif

            @if(count($files) > 0)
                <div class="flex flex-col items-center space-y-6">
                    @foreach($files as $file)
                        <a ping="{{ route('files.increment-visits', ['id' => $file->id]) }}"
                           href="{{ asset($file->file_url) }}"
                           target="_blank"
                           class="w-3/4 p-4 bg-gray-200 shadow-lg rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-gray-50 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6 text-gray-500 group-hover:text-gray-600" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span class="text-lg font-medium text-gray-700 group-hover:text-gray-800">
                                        {{ $file->file_name ?: 'Archivo PDF' }}
                                    </span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5 text-gray-400 group-hover:text-gray-600" fill="none"
                                     viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center p-8 bg-gray-50 rounded-xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <p class="text-2xl text-gray-600">No hay archivos disponibles</p>
                </div>
            @endif
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileLinks = document.querySelectorAll('a[ping]');
            const isFirefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;

            // console.log('Soy firefox?', isFirefox);

            if (isFirefox) {
                // console.log('Soy firefox');
                fileLinks.forEach(link => {
                    const pingUrl = link.getAttribute('ping');
                    // link.removeAttribute('ping');

                    link.addEventListener('click', function(e) {
                        fetch(pingUrl)
                            // .then(() => console.log('visitado', pingUrl))
                            .catch(error => {
                                console.log('Error registrando la visita:', error);
                            });
                    });
                });
            }
        });
    </script>
@endpush