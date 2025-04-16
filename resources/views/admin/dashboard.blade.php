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
                    <div class="bg-indigo-700 p-3 rounded-full text-indigo-200">
                        <x-icons.file-icon />
                    </div>
                </div>
            </div>

            <!-- Peso Total -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-200">Peso Total de Archivos</h2>
                        <p class="text-3xl font-bold text-emerald-400 mt-2">
                            {{ $formattedFileSize }}
                        </p>
                    </div>
                    <div class="bg-emerald-700 p-3 rounded-full">
                        <x-icons.weight-icon />
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
                    @forelse($topClients as $client)
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-medium text-gray-200">{{ $client->legal_name }}</span>
                            </div>
                            <span class="px-3 py-1 bg-blue-900 text-blue-200 rounded-full text-sm font-medium">
                                {{ $client->files_count }} archivos
                            </span>
                        </div>
                    @empty
                        <div class="text-center p-4 bg-gray-700 rounded-lg">
                            <p class="text-gray-400">No hay clientes con archivos</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Productos -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-200 mb-4">Top Productos más Visitados</h2>
                <div class="space-y-4">
                    @forelse($topProducts as $product)
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-medium text-gray-200">{{ $product->name }}</span>
                            </div>
                            <span class="px-3 py-1 bg-purple-900 text-purple-200 rounded-full text-sm font-medium">
                                {{ $product->visit_count }} visitas
                            </span>
                        </div>
                    @empty
                        <div class="text-center p-4 bg-gray-700 rounded-lg">
                            <p class="text-gray-400">No hay productos visitados</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Top Archivos -->
            <div class="bg-gray-800 rounded-xl shadow-lg p-6">
                <h2 class="text-xl font-semibold text-gray-200 mb-4">Top Links más Visitados</h2>
                <div class="space-y-4">
                    @forelse($topQRs as $qr)
                        <div class="flex items-center justify-between p-3 bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-medium text-gray-200">{{ $qr->file_name }}</span>
                            </div>
                            <span class="px-3 py-1 bg-amber-900 text-amber-200 rounded-full text-sm font-medium">
                                {{ $qr->visits_count }} visitas
                            </span>
                        </div>
                    @empty
                        <div class="text-center p-4 bg-gray-700 rounded-lg">
                            <p class="text-gray-400">No hay archivos visitados</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
