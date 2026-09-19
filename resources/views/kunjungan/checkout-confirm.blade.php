@extends('layouts.kunjungan')
@section('title', 'Konfirmasi Checkout')
@section('content')

<div class="fixed inset-0 -z-10 bg-cover bg-center"
     style="background-image: url('{{ asset('images/background_visitor.jpg') }}');"></div>

<div class="rounded-2xl shadow-lg p-6 border border-white/50"
     style="background-color: rgba(255,255,255,0.4); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">

    <img src="{{ asset('images/logo_jafran.png') }}" alt="Logo Jafran"
         class="mx-auto mb-4 object-contain"
         style="width: 80px; height: auto;">

    <h1 class="text-xl font-bold mb-1 text-corn-dark text-center">Konfirmasi Checkout</h1>
    <p class="text-corn-dark/80 text-sm mb-6 text-center">
        Anda akan mengakhiri kunjungan sebagai <strong>{{ $visit->visitor->name }}</strong>.
    </p>

    <div class="mb-6 p-4 rounded-xl text-sm text-corn-dark" style="background-color: rgba(228,233,216,0.6);">
        <p><span class="font-medium">Keperluan:</span> {{ $visit->purpose }}</p>
        <p><span class="font-medium">Check-in:</span> {{ $visit->checked_in_at?->format('d M Y, H:i') ?? '-' }}</p>
    </div>

    <form method="POST" action="{{ route('kunjungan.store-checkout') }}">
        @csrf
        <button type="submit" class="w-full bg-corn-green hover:bg-corn-caramel transition text-white font-medium py-3 rounded-lg">
            Ya, Checkout Sekarang
        </button>
    </form>
</div>
@endsection