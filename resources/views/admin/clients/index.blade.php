@extends('layouts.admin')
@section('title', __('general.clients'))
@section('admin')
    @if(session('success'))
        @component('components.alert', ['variant' => 'success'])
            {{ __(session('success')) }}
        @endcomponent
    @endif
    <x-heading1>
        {{ __('general.clients') }}
    </x-heading1>
    <x-button-link href="{{ route('admin.new.client') }}" class="btn-success">
        {{ __('general.new_client') }}
    </x-button-link>
    @include('admin.clients.forms.searcher')
    <x-table-default>
        <thead class="bg-accent-content">
        <tr>
            <th>#</th>
            <th>{{ __('clients.legal_name') }}</th>
            <th>{{ __('clients.tax_id') }}</th>
            <th>{{ __('clients.contact_name') }}</th>
            <th>{{ __('clients.contact_email') }}</th>
            <th>{{ __('clients.contact_phone') }}</th>
            <th>{{ __('clients.legal_address') }}</th>
            <th>{{ __('clients.products') }}</th>
            <th>{{ __('clients.files') }}</th>
            <th>{{ __('clients.qrs') }}</th>
            <th>{{ __('general.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($clients as $client)
            <tr>
                <td data-label="#">{{ $client->id }}</td>
                <td data-label="{{ __('clients.legal_name') }}">{{ $client->legal_name }}</td>
                <td data-label="{{ __('clients.tax_id') }}">{{ $client->tax_id }}</td>
                <td data-label="{{ __('clients.contact_name') }}">{{ $client->contact_name }}</td>
                <td data-label="{{ __('clients.contact_email') }}">{{ $client->contact_email }}</td>
                <td data-label="{{ __('clients.contact_phone') }}">{{ $client->contact_phone }}</td>
                <td data-label="{{ __('clients.legal_address') }}">{{ $client->legal_address }}</td>
                <td data-label="{{ __('clients.products') }}">
                    <span class="tooltip" data-tip="{{ __('products.view_products') }}">
                        <a href="{{ route('admin.products', ['client' => $client->id]) }}"
                           class="badge text-xs badge-primary gap-1 cursor-pointer hover:brightness-90">
                            {{ $client->products()->count() }}
                            <x-icons.external-link class="h-4 w-4 text-white" />
                        </a>
                    </span>
                </td>
                <td data-label="{{ __('clients.files') }}">
                    <span class="tooltip" data-tip="{{ __('files.view_files') }}">
                        <a href="{{ route('admin.files', ['client' => $client->id]) }}"
                           class="badge text-xs badge-primary gap-1 cursor-pointer hover:brightness-90">
                            {{ $client->files_count }}
                            <x-icons.external-link class="h-4 w-4 text-white" />
                        </a>
                    </span>
                </td>
                <td data-label="{{ __('clients.qrs') }}">
                    <span class="tooltip" data-tip="{{ __('qrs.view_qrs') }}">
                        <a href="{{ route('admin.qrs', ['client' => $client->id]) }}"
                           class="badge text-xs badge-primary gap-1 cursor-pointer hover:brightness-90">
                            {{ $client->qrs()->count() }}
                            <x-icons.external-link class="h-4 w-4 text-white" />
                        </a>
                    </span>
                </td>
                <td data-label="{{ __('general.actions') }}">
                    <div class="flex flex-col sm:flex-row sm:gap-x-1 items-center gap-y-2.5">
                        <x-button-link
                                href="{{ route('admin.edit.client', ['id' => $client->id]) }}"
                                class="btn-xs btn-warning btn-soft max-sm:w-full"
                        >
                            {{ __('general.edit') }}
                        </x-button-link>
                        @if($client->deleted_at === null)
                            <button class="btn btn-xs btn-error btn-soft btn-delete-button max-sm:w-full"
                                    data-id="{{'modal-' . $client->id }}">
                                {{ __('general.delete') }}
                            </button>
                            <dialog id="{{'modal-' . $client->id }}" class="modal">
                                <div class="modal-box">
                                    <h3 class="text-lg font-bold">{{ __('clients.confirm_delete') }}</h3>
                                    <small class="py-2 text-xs">{{ __('general.press_esc') }}</small>
                                    <div class="modal-action mt-2.5">
                                        <div class="w-full">
                                            <form id="{{ 'delete-user-' . $client->id }}"
                                                  action="{{ route('admin.client.delete', ['id' => $client->id]) }}"
                                                  method="post"
                                                  class="w-full grid grid-cols-1 gap-2.5 delete-button">
                                                <p class="mb-5 mt-3">{!! __('clients.type_name_to_delete', ['name' => $client->legal_name]) !!}</p>
                                                @method('DELETE')
                                                @csrf
                                                <x-forms.floating-input
                                                        type="text"
                                                        name="{{ $client->id }}_legal_name"
                                                        id="{{ $client->id }}_legal_name"
                                                        label="{{ __('clients.legal_name') }}"
                                                        placeholder="{{ __('clients.legal_name') }}"
                                                        required="{{ true }}"
                                                />
                                            </form>
                                            <div class="flex flex-wrap lg:justify-end items-center mt-2.5 gap-2.5">
                                                <form method="dialog">
                                                    <button class="btn">{{ __('general.close') }}</button>
                                                </form>
                                                <button type="submit"
                                                        form="{{'delete-user-' . $client->id }}"
                                                        disabled
                                                        class="btn btn-soft btn-error">{{ __('clients.delete_client') }}
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </dialog>
                        @else
                            <form
                                    action="{{ route('admin.client.restore', ['id' => $client->id]) }}"
                                    method="post"
                                    class="inline"
                            >
                                @csrf
                                @method('PATCH')
                                <button class="btn btn-xs btn-success btn-soft">
                                    {{ __('general.restore') }}
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center text-2xl bg-content-200 py-2.5"
                    colspan="100%">{{ __('clients.no_clients') }}</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>
    {{  $clients->appends(request()->query())->links() }}
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('.delete-button');
            const buttons_delete = document.querySelectorAll('.btn-delete-button');
            buttons_delete.forEach(button => {
                button.addEventListener('click', () => {
                    const modal = document.getElementById(button.getAttribute('data-id'));
                    if (modal) {
                        modal.showModal();
                    }
                });
            });

            forms.forEach(form => {
                const input = form.querySelector('input[type="text"]');
                const client_value = form.querySelector('.text-error').textContent;
                const button = form.parentElement.querySelector('button[type="submit"]');

                input.addEventListener('input', e => {
                    const value = e.target.value;
                    if (value === client_value) {
                        button.removeAttribute('disabled');
                        button.classList.remove('btn-soft');
                    } else {
                        button.setAttribute('disabled', 'disabled');
                        button.classList.add('btn-soft');
                    }
                });
            });
        });
    </script>
@endpush