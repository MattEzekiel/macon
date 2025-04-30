@extends('layouts.admin')
@section('title', __('general.files'))
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
        {{ __('general.files') }}
    </x-heading1>

    @include('admin.files.forms.searcher')

    <x-table-default>
        <thead class="bg-accent-content">
        <tr>
            <th>#</th>
            <th>{{ __('files.file_name') }}</th>
            <th>{{ __('files.original_file_name') }}</th>
            <th>{{ __('files.product') }}</th>
            <th>{{ __('files.client') }}</th>
            <th>{{ __('files.file_size') }}</th>
            <th>{{ __('files.created_at') }}</th>
            <th>{{ __('files.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($files as $file)
            <tr>
                <td>{{ $file->id }}</td>
                <td>{{ $file->file_name ?: $file->original_file_name }}</td>
                <td>{{ $file->original_file_name }}</td>
                <td>
                    <div class="tooltip" data-tip="{{ __('general.view') }} {{ $file->product->name }}">
                        <a href="{{ route('admin.products', ['client' => $file->product->client_id, 'name' => $file->product->name]) }}"
                           class="flex items-center gap-1 text-base-content font-bold hover:underline hover:text-base-content/80 transition-all duration-300">
                            {{ $file->product->name }}
                            <x-icons.external-link class="h-4 w-4" />
                        </a>
                    </div>
                </td>
                <td>
                    <div class="tooltip" data-tip="{{ __('general.view') }} {{ $file->product->client->legal_name }}">
                        <a href="{{ route('admin.clients', ['client' => $file->product->client_id]) }}"
                           class="flex items-center gap-1 text-base-content font-bold hover:underline hover:text-base-content/80 transition-all duration-300">
                            {{ $file->product->client->legal_name }}
                            <x-icons.external-link class="h-4 w-4" />
                        </a>
                    </div>
                </td>
                <td>{{ $file->formatFileSize($file->file_size) }}</td>
                <td>{{ $file->created_at->format('d/m/Y') }}</td>
                <td>
                    <div class="flex items-center gap-2">
                        <a href="{{ asset($file->file_url) }}"
                           target="_blank"
                           class="btn btn-xs btn-primary btn-soft">
                            {{ __('files.view_file') }}
                        </a>
                        <a href="{{ route('admin.edit.files', ['id' => $file->product_id]) }}"
                           class="btn btn-xs btn-warning btn-soft">
                            {{ __('general.edit') }}
                        </a>
                    </div>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="8" class="text-center text-2xl bg-content-200 py-2.5">{{ __('files.no_files') }}</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>

    <div class="mt-4">
        {{ $files->links() }}
    </div>
@endsection 