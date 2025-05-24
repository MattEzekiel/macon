@extends('layouts.admin')
@section('title', __('general.contact'))
@section('admin')
    <x-heading1>
        {{ __('general.contact') }}
    </x-heading1>
    <x-table-default>
        <thead class="bg-accent-content">
        <tr>
            <th>#</th>
            {{--            <th>{{ __('general.name') }}</th>--}}
            <th>{{ __('general.email') }}</th>
            <th>{{ __('general.subject') }}</th>
            <th>{{ __('general.actions') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($contacts as $contact)
            <tr>
                <td data-label="#">{{ $contact->id }}</td>
                {{--                <td data-label="{{ __('general.name') }}">{{ $contact->name }}</td>--}}
                <td data-label="{{ __('general.email') }}">{{ $contact->email }}</td>
                <td data-label="{{ __('general.subject') }}">{{ $contact->subject }}</td>
                <td data-label="{{ __('general.actions') }}">
                    <x-button-link
                            href="{{ route('admin.contact.show', ['id' => $contact->id]) }}"
                            class="btn-xs btn-soft max-sm:w-full"
                    >
                        {{ __('general.show') }}
                    </x-button-link>
                </td>
            </tr>
        @empty
            <tr>
                <td class="text-center text-2xl bg-content-200 py-2.5"
                    colspan="100%">{{ __('general.no_messages') }}</td>
            </tr>
        @endforelse
        </tbody>
    </x-table-default>
    {{  $contacts->appends(request()->query())->links() }}
@endsection
