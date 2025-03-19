@extends('layouts.auth')
@section('auth')
    <x-main-container class="grid place-items-center h-screen">
        <form action="{{ route('admin.login') }}" method="post"
              class="md:w-1/4 mx-auto border border-white rounded shadow p-10 space-y-5">
            <x-heading1>Ingrese sus credenciales</x-heading1>
            @if(session('error'))
                <x-alert type="error">{{ session('error') }}</x-alert>
            @endif
            @csrf
            <x-forms.floating-input
                    type="email"
                    name="email"
                    id="email"
                    label="Email"
                    placeholder="Ingrese su mail"
                    required={{ true }}
            />
            <x-forms.floating-input
                    type="password"
                    name="password"
                    id="password"
                    label="Contraseña"
                    placeholder="Ingrese su contraseña"
                    required={{ true }}
            />
            <div class="float-end">
                <x-forms.submit-button>
                    Ingresar
                </x-forms.submit-button>
            </div>
        </form>
    </x-main-container>
@endsection
