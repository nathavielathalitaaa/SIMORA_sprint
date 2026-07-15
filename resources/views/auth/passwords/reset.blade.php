@extends('layouts.app')

@section('content')
<div class="hivi-page-wrapper">
    <div class="mb-8">
        <h1 class="text-3xl font-sans font-bold text-[#111111]">{{ __('Setel Ulang Kata Sandi') }}</h1>
        <p class="text-[13px] font-light text-[#6B7280] mt-1">
            {{ __('Masukkan kata sandi baru Anda.') }}
        </p>
    </div>
    
    <div class="hivi-card max-w-[480px] mx-auto">
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-[#6B7280] mb-2">
                    {{ __('Alamat Email') }}
                </label>
                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                    class="hivi-input" placeholder="name@company.com">

                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-[#6B7280] mb-2">
                    {{ __('Kata Sandi Baru') }}
                </label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="hivi-input" placeholder="Minimal 8 karakter">

                @error('password')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password-confirm" class="block text-sm font-medium text-[#6B7280] mb-2">
                    {{ __('Konfirmasi Kata Sandi') }}
                </label>
                <input id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="hivi-input" placeholder="Ulangi kata sandi baru">
            </div>

            <button type="submit" class="hivi-btn-primary w-full">
                {{ __('Setel Ulang Kata Sandi') }}
            </button>
        </form>
    </div>
</div>
@endsection
