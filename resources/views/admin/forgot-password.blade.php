@extends('layouts.auth')
@section('title', __('general.forgot_password'))
@section('auth')
    <x-main-container class="grid place-items-center h-screen">
        <div class="md:w-1/4 mx-auto border border-white rounded shadow p-10">
            <form action="{{ route('admin.restore.password') }}" method="post" class="space-y-5">
                <x-heading1>Le enviaremos un email para recuperar su contraseña</x-heading1>
                @if(session('error'))
                    @component('components.alert', ['variant' => 'error'])
                        {{ __(session('error')) }}
                    @endcomponent
                @elseif(session('success'))
                    @component('components.alert', ['variant' => 'success'])
                        {{ __(session('success')) }}
                    @endcomponent
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
                <div class="lg:grid lg:place-items-end mb-3 lg:mb-5">
                    <x-forms.submit-button>
                        Recuperar
                    </x-forms.submit-button>
                </div>
            </form>
            <p>¿Ya tiene una cuenta? <a href="{{ route('admin.login') }}" class="text-accent">Inicie sesión</a></p>
        </div>
    </x-main-container>
@endsection
