@extends('layouts.auth')

@section('auth')
    <x-main-container class="grid place-items-center h-screen">
        <form method="post" class="md:w-1/2 mx-auto border border-white rounded shadow p-10">
            @csrf
            <x-forms.floating-input
                type="email"
                name="email"
                id="email"
                label="Su email"
                placeholder="Ingresa tu mail"
            />
        </form>
    </x-main-container>
@endsection
