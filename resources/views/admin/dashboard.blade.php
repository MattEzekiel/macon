@extends('layouts.admin')

@section('admin')
    <div class="p-6">
        <x-heading1>
            Dashboard
        </x-heading1>

        <!-- Resumen General -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Total de Archivos -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-200">Total de Archivos</h2>
                        <p class="text-3xl font-bold text-indigo-400 mt-2">{{ number_format($totalFiles) }}</p>
                    </div>
                    <div class="bg-indigo-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Peso Total -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-200">Peso Total de Archivos</h2>
                        <p class="text-3xl font-bold text-emerald-400 mt-2">
                            @php
                                $size = $totalFileSize;
                                $unit = 'B';
                                if ($size > 1024) {
                                    $size = $size / 1024;
                                    $unit = 'KB';
                                }
                                if ($size > 1024) {
                                    $size = $size / 1024;
                                    $unit = 'MB';
                                }
                                if ($size > 1024) {
                                    $size = $size / 1024;
                                    $unit = 'GB';
                                }
                                echo number_format($size, 2) . ' ' . $unit;
                            @endphp
                        </p>
                    </div>
                    <div class="bg-emerald-700 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rankings -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Top Clientes -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-200 mb-4">Top Clientes con más Archivos</h2>
                <div class="space-y-4">
                    @foreach($topClients as $client)
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-medium text-gray-200">{{ $client->legal_name }}</span>
                            </div>
                            <span class="px-3 py-1 bg-blue-900 text-blue-200 rounded-full text-sm font-medium">
                                {{ $client->files_count }} archivos
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Productos -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-200 mb-4">Top Productos más Visitados</h2>
                <div class="space-y-4">
                    @foreach($topProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-medium text-gray-200">{{ $product->name }}</span>
                            </div>
                            <span class="px-3 py-1 bg-purple-900 text-purple-200 rounded-full text-sm font-medium">
                                {{ $product->visit_count }} visitas
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Top Archivos -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-200 mb-4">Top Links más Visitados</h2>
                <div class="space-y-4">
                    @foreach($topQRs as $qr)
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-medium text-gray-200">{{ $qr->file_name }}</span>
                            </div>
                            <span class="px-3 py-1 bg-amber-900 text-amber-200 rounded-full text-sm font-medium">
                                {{ $qr->visits_count }} visitas
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
