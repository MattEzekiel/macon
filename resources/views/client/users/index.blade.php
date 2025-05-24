@extends('layouts.admin')
@section('title', __('general.users'))
@section('admin')
    @if(session('success'))
        @component('components.alert', ['variant' => 'success'])
            {{ __(session('success')) }}
        @endcomponent
    @endif
    <x-heading1>
        {{ __('general.users') }}
    </x-heading1>
    <x-button-link href="{{ route('client.new.user') }}" class="btn-success">
        {{ __('users.create_new_user') }}
    </x-button-link>
    @include('client.users.forms.searcher')
    <x-table-default>
        <thead>
        <tr>
            <th>#</th>
            <th>{{ __('users.name') }}</th>
            <th>{{ __('users.email') }}</th>
            <th>{{ __('general.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($users as $user)
            <tr>
                <td data-label="#">{{ $user->id }}</td>
                <td data-label="{{ __('users.name') }}">{{ $user->name }}</td>
                <td data-label="{{ __('users.email') }}">{{ $user->email }}</td>
                <td data-label="{{ __('general.actions') }}">
                    <div class="flex flex-col sm:flex-row sm:gap-x-1 items-center gap-y-2.5">
                        <x-button-link
                                href="{{ route('client.edit.user', ['id' => $user->id]) }}"
                                class="btn-xs btn-warning btn-soft max-sm:w-full"
                        >
                            {{ __('general.edit') }}
                        </x-button-link>
                        <button class="btn btn-xs btn-error btn-soft btn-delete-button max-sm:w-full"
                                data-id="{{'modal-' . $user->id }}">
                            {{ __('general.delete') }}
                        </button>
                        <dialog id="{{'modal-' . $user->id }}" class="modal">
                            <div class="modal-box">
                                <h3 class="text-lg font-bold">{{ __('users.confirm_delete') }}</h3>
                                <small class="py-2 text-xs">{{ __('general.press_esc') }}</small>
                                <div class="modal-action mt-2.5">
                                    <div class="w-full">
                                        <form id="{{ 'delete-user-' . $user->id }}"
                                              action="{{ route('client.user.delete', ['id' => $user->id]) }}"
                                              method="post"
                                              class="w-full grid grid-cols-1 gap-2.5 delete-button">
                                            <p class="mb-5 mt-3">{!! __('users.type_name_to_delete', ['name' => $user->name]) !!}</p>
                                            @method('DELETE')
                                            @csrf
                                            <x-forms.floating-input
                                                    type="text"
                                                    name="{{ $user->id }}_name"
                                                    id="{{ $user->id }}_name"
                                                    label="{{ __('users.name') }}"
                                                    placeholder="{{ __('users.name') }}"
                                                    required="true"
                                            />
                                        </form>
                                        <div class="flex flex-wrap lg:justify-end items-center mt-2.5 gap-2.5">
                                            <form method="dialog">
                                                <button class="btn">{{ __('general.close') }}</button>
                                            </form>
                                            <button type="submit"
                                                    form="{{'delete-user-' . $user->id }}"
                                                    disabled
                                                    class="btn btn-soft btn-error">{{ __('users.delete_user') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </dialog>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center text-2xl bg-content-200 py-2.5" colspan="100%">{{ __('users.no_users') }}</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>
    {{ $users->appends(request()->query())->links() }}
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