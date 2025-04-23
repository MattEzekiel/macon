@extends('layouts.auth')
@section('title', __('general.forgot_password'))
@section('auth')
    <x-main-container class="grid place-items-center h-screen">
        <div class="md:w-1/4 mx-auto border border-white rounded shadow p-10">
            <form action="{{ route('admin.restore.password') }}" method="post" class="space-y-5">
                <x-heading1>{{ __('auth.forgot_password_message') }}</x-heading1>
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
                <div class="lg:grid lg:place-items-end mb-3 lg:mb-5">
                    <x-forms.submit-button>
                        {{ __('auth.recover_password') }}
                    </x-forms.submit-button>
                </div>
            </form>
            <p>{{ __('auth.already_have_account') }} <a href="{{ route('admin.login') }}"
                                                        class="text-accent">{{ __('auth.login_here') }}</a></p>
        </div>
    </x-main-container>
@endsection