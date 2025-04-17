@extends('layouts.auth')
@section('title', __('general.forgot_password'))
@section('auth')
    <x-main-container class="grid place-items-center h-screen">
        <div class="md:w-1/4 mx-auto border border-white rounded shadow p-10">
            <form action="{{ route('admin.reset.password') }}" method="post" class="space-y-5">
                <x-heading1>Escriba su nueva contraseña</x-heading1>
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
                <input type="hidden" name="token" id="token" value="{{ $token }}">
                <x-forms.floating-input
                        type="email"
                        name="email"
                        id="email"
                        label="Email"
                        error="{{ $errors->has('email') ? $errors->first('email') : null }}"
                        placeholder="Ingrese su mail"
                        required={{ true }}
                />
                <x-forms.floating-input
                        type="password"
                        name="password"
                        id="password"
                        label="Contraseña"
                        error="{{ $errors->has('password') ? $errors->first('password') : null }}"
                        placeholder="Ingrese su nueva contraseña"
                        required={{ true }}
                />
                <x-forms.floating-input
                        type="password"
                        name="password_confirmed"
                        id="password_confirmed"
                        label="Confirmar contraseña"
                        error="{{ $errors->has('password_confirmed') ? $errors->first('password_confirmed') : null }}"
                        placeholder="Confirmar contraseña"
                        required={{ true }}
                />
                <div class="lg:float-end">
                    <x-forms.submit-button>
                        Restablecer contraseña
                    </x-forms.submit-button>
                </div>
            </form>
        </div>
    </x-main-container>
@endsection
