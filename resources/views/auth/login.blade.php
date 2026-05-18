@extends('layouts.guest')

@section('content')
    <div class="fixed inset-0 z-0 pointer-events-none bg-cover bg-center bg-no-repeat"
         style="background-image: url('{{ asset('images/background-church.jpg') }}'); filter: brightness(0.6);">
    </div>

    <div class="relative z-10 w-full flex flex-col items-center">

        <div class="mb-6">
            <a href="/">
                <img src="{{ asset('images/Logo.png') }}" alt="Logo YHS" class="h-24 w-auto drop-shadow-lg">
            </a>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="w-full">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                    :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            {{-- Password dengan tombol show/hide --}}
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <div style="position:relative;">
                    <x-text-input id="password" class="block mt-1 w-full"
                        type="password"
                        name="password"
                        required autocomplete="current-password"
                        style="padding-right: 44px;" />
                    <button type="button" onclick="togglePassword('password', 'eye-login')"
                        style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#9ca3af; padding:4px;">
                        <i id="eye-login" class="fas fa-eye-slash" style="font-size:15px;"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center cursor-pointer">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                       href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
                <x-primary-button type="submit" class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>

        @if (!auth()->check())
            <div class="hero-info mt-6 text-sm text-gray-600 text-center">
                Belum punya akun?
                <a href="{{ route('register') }}" class="underline font-semibold hover:text-indigo-600">Daftar sekarang</a>
                dan jadilah bagian dari keluarga rohani kami.
            </div>
        @endif
    </div>

    <script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }
    </script>
@endsection