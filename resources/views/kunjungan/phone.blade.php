@extends('layouts.kunjungan')
@section('title', 'Selamat Datang')
@section('content')

<div class="fixed inset-0 -z-10 bg-cover bg-center"
     style="background-image: url('{{ asset('images/background_visitor.jpg') }}');"></div>

<div class="rounded-2xl shadow-lg p-6 border border-white/50"
     style="background-color: rgba(255,255,255,0.4); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">

    <img src="{{ asset('images/logo_jafran.png') }}" alt="Logo Jafran"
         class="mx-auto mb-4 object-contain"
         style="width: 120px; height: auto;">

    <h1 class="text-xl font-bold mb-2 text-corn-dark text-center">Selamat Datang</h1>
    <p class="text-corn-dark/80 mb-6 text-center">Masukkan nomor HP Anda untuk memulai.</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('kunjungan.check-phone') }}">
        @csrf
        <label class="block text-sm font-medium text-corn-dark mb-1">Nomor HP</label>
        <input type="tel" name="phone_number" required placeholder="08xxxxxxxxxx"
            class="w-full border border-corn-sage bg-white/70 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-corn-green">
        <button type="submit" class="w-full bg-corn-green hover:bg-corn-caramel transition text-white font-medium py-3 rounded-lg">
            Lanjut
        </button>
    </form>
</div>
@endsection