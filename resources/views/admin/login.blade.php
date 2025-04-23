@extends('layouts.auth')
@section('title', __('general.login'))
@section('auth')
    <x-main-container class="grid place-items-center h-screen">
        <div class="md:w-1/4 mx-auto border border-white rounded shadow p-10">
            <form action="{{ route('admin.login') }}" method="post" class="space-y-5">
                <x-heading1>{{ __('auth.login_credentials') }}</x-heading1>
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
                        label="{{ __('auth.email') }}"
                        placeholder="{{ __('auth.enter_email') }}"
                        required={{ true }}
                />
                <x-forms.floating-input
                        type="password"
                        name="password"
                        id="password"
                        label="{{ __('auth.password') }}"
                        placeholder="{{ __('auth.enter_password') }}"
                        required={{ true }}
                />
                <div class="lg:grid lg:place-items-end mb-3 lg:mb-5">
                    <x-forms.submit-button>
                        {{ __('auth.login') }}
                    </x-forms.submit-button>
                </div>
            </form>
            <p class="text-sm text-center">{{ __('auth.forgot_password') }} <a href="{{ route('admin.forgot-password.form') }}"
                                                                     class="text-accent">{{ __('auth.recover') }}</a></p>
        </div>
    </x-main-container>
@endsection
