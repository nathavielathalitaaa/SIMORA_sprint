@extends('layouts.app')

@section('content')
<div class="hivi-page-wrapper">
    <div class="mb-8">
        <h1 class="text-3xl font-sans font-bold text-[#111111]">{{ __('Setel Ulang Kata Sandi') }}</h1>
        <p class="text-[13px] font-light text-[#6B7280] mt-1">
            {{ __('Masukkan alamat email Anda untuk menerima link reset kata sandi.') }}
        </p>
    </div>
    
    <div class="hivi-card max-w-[480px] mx-auto">
        @if (session('status'))
            <div class="mb-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-700">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-[#6B7280] mb-2">
                    {{ __('Alamat Email') }}
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                    class="hivi-input" placeholder="name@company.com">

                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="hivi-btn-primary w-full">
                {{ __('Kirim Link Setel Ulang Kata Sandi') }}
            </button>
        </form>
    </div>
</div>
@endsection
