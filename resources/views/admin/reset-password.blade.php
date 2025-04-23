@extends('layouts.auth')
@section('title', __('general.forgot_password'))
@section('auth')
    <x-main-container class="grid place-items-center h-screen">
        <div class="md:w-1/4 mx-auto border border-white rounded shadow p-10">
            <form action="{{ route('admin.reset.password') }}" method="post" class="space-y-5">
                <x-heading1>{{ __('auth.reset_password') }}</x-heading1>
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
                        label="{{ __('auth.email') }}"
                        error="{{ $errors->has('email') ? $errors->first('email') : null }}"
                        placeholder="{{ __('auth.enter_email') }}"
                        required={{ true }}
                />
                <x-forms.floating-input
                        type="password"
                        name="password"
                        id="password"
                        label="{{ __('auth.password') }}"
                        error="{{ $errors->has('password') ? $errors->first('password') : null }}"
                        placeholder="{{ __('auth.enter_new_password') }}"
                        required={{ true }}
                />
                <x-forms.floating-input
                        type="password"
                        name="password_confirmed"
                        id="password_confirmed"
                        label="{{ __('auth.confirm_password') }}"
                        error="{{ $errors->has('password_confirmed') ? $errors->first('password_confirmed') : null }}"
                        placeholder="{{ __('auth.confirm_new_password') }}"
                        required={{ true }}
                />
                <div class="lg:float-end">
                    <x-forms.submit-button>
                        {{ __('auth.reset_password_button') }}
                    </x-forms.submit-button>
                </div>
            </form>
        </div>
    </x-main-container>
@endsection
