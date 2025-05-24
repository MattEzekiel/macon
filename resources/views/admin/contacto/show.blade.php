@extends('layouts.admin')
@section('title', __('general.contact'), ['name' => $contact->id])
@section('admin')
    <x-heading1>
        {{ __('general.contact') }} : <b>#{{ $contact->id }}</b>
    </x-heading1>
    <div class="mt-3.5">
        <dl class="space-y-3.5 grid md:grid-cols-2 w-fit mx-auto bg-base-300 rounded-xl shadow-lg p-6 hover:bg-base-200 transition duration-300 ease-in-out hover:shadow-xl">
            {{--<dt>{{ __('general.name') }}</dt>
            <dd>{{ $contact->name }}:</dd>--}}
            <dt>{{ __('general.email') }}:</dt>
            <dd>{{ $contact->email }}</dd>
            <dt>{{ __('general.subject') }}:</dt>
            <dd>{{ $contact->subject }}</dd>
            <dt>{{ __('general.message') }}:</dt>
            <dd class="whitespace-pre-wrap col-span-2">{{ $contact->message }}</dd>
            <dt>{{ __('general.created_at') }}:</dt>
            <dd>{{ $contact->created_at->format('d/m/Y H:i:s') }}</dd>
        </dl>
    </div>
@endsection