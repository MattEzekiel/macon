@extends('layouts.general')
@section('general')
    <main class="min-h-screen bg-gradient-to-b from-base-200 to-base-300 p-6">
        <div class="max-w-2xl mx-auto">
            @if($product)
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold mb-2 text-base-content">{{ $product->name }}</h1>
                    <p class="text-xl text-base-content/80">{{ $product->client->legal_name }}</p>
                </div>
            @endif

            @if(count($files) > 0)
                <div class="space-y-4">
                    @foreach($files as $file)
                        <a href="{{ asset($file->file_url) }}" 
                           target="_blank"
                           class="block w-full p-4 bg-white shadow-lg rounded-xl transition-transform hover:scale-105 hover:shadow-xl">
                            <div class="flex items-center justify-between">
                                <span class="text-lg font-medium text-gray-800">
                                    {{ $file->file_name ?: 'Archivo PDF' }}
                                </span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="text-center p-8 bg-white rounded-xl shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <p class="text-2xl text-gray-600">No hay archivos disponibles</p>
                </div>
            @endif
        </div>
    </main>
@endsection
