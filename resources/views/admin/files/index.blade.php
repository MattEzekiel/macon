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
                <td data-label="#">{{ $file->id }}</td>
                <td data-label="{{ __('files.file_name') }}">{{ $file->file_name ?: $file->original_file_name }}</td>
                <td data-label="{{ __('files.original_file_name') }}">{{ $file->original_file_name }}</td>
                <td data-label="{{ __('files.product') }}">
                    <span class="tooltip" data-tip="{{ __('general.view') }} {{ $file->product->name }}">
                        <a href="{{ route('admin.products', ['client' => $file->product->client_id, 'name' => $file->product->name]) }}"
                           class="flex items-center gap-1 text-base-content font-bold hover:underline hover:text-base-content/80 transition-all duration-300">
                            {{ $file->product->name }}
                            <x-icons.external-link class="h-4 w-4" />
                        </a>
                    </span>
                </td>
                <td data-label="{{ __('files.client') }}">
                    <span class="tooltip" data-tip="{{ __('general.view') }} {{ $file->product->client->legal_name }}">
                        <a href="{{ route('admin.clients', ['client' => $file->product->client_id]) }}"
                           class="flex items-center gap-1 text-base-content font-bold hover:underline hover:text-base-content/80 transition-all duration-300">
                            {{ $file->product->client->legal_name }}
                            <x-icons.external-link class="h-4 w-4" />
                        </a>
                    </span>
                </td>
                <td data-label="{{ __('files.file_size') }}">{{ $file->formatFileSize($file->file_size) }}</td>
                <td data-label="{{ __('files.created_at') }}">{{ $file->created_at->format('d/m/Y') }}</td>
                <td data-label="{{ __('files.actions') }}">
                    <div class="flex flex-col sm:flex-row sm:gap-x-1 items-center gap-y-2.5">
                        <a href="{{ asset($file->file_url) }}"
                           target="_blank"
                           class="btn btn-xs btn-primary btn-soft max-sm:w-full">
                            {{ __('files.view_file') }}
                        </a>
                        <a href="{{ route('admin.edit.files', ['id' => $file->product_id]) }}"
                           class="btn btn-xs btn-warning btn-soft max-sm:w-full">
                            {{ __('general.edit') }}
                        </a>
                    </div>
                </td>
            </tr>

        @empty
            <tr>
                <td colspan="100%" class="text-center text-2xl bg-content-200 py-2.5">{{ __('files.no_files') }}</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>

    <div class="mt-4">
        {{ $files->links() }}
    </div>
@endsection 