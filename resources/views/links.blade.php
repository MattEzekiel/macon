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
                           href="{{ route('files.view', ['id' => $file->id]) }}"
                           target="_blank"
                           class="w-3/4 p-4 bg-gray-200 shadow-lg rounded-xl transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-gray-50 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <x-icons.file-icon class="h-6 w-6 text-gray-500 group-hover:text-gray-600" />     
                                    <span class="text-lg font-medium text-gray-700 group-hover:text-gray-800">
                                        {{ $file->file_name ?: __('files.pdf_file') }}
                                    </span>
                                </div>
                                    <x-icons.external-link class="h-5 w-5 text-gray-400 group-hover:text-gray-600"/>
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
                    <p class="text-2xl text-gray-600">{{ __('files.no_files') }}</p>
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