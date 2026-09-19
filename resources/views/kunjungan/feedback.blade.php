@extends('layouts.kunjungan')
@section('title', 'Beri Feedback')
@section('content')

<div class="fixed inset-0 -z-10 bg-cover bg-center"
     style="background-image: url('{{ asset('images/background_visitor.jpg') }}');"></div>

<div class="rounded-2xl shadow-lg p-6 border border-white/50"
     style="background-color: rgba(255,255,255,0.4); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">

    <img src="{{ asset('images/logo_jafran.png') }}" alt="Logo Jafran"
         class="mx-auto mb-4 object-contain"
         style="width: 80px; height: auto;">

    <h1 class="text-xl font-bold mb-1 text-corn-dark text-center">Bagaimana Kunjungan Anda?</h1>
    <p class="text-corn-dark/80 text-sm mb-6 text-center">Feedback Anda membantu kami meningkatkan layanan (opsional).</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('kunjungan.store-feedback') }}" class="mb-3">
        @csrf

        <div class="flex justify-between mb-6">
            @foreach ([1,2,3,4,5] as $star)
                <label class="cursor-pointer text-center">
                    <input type="radio" name="rating" value="{{ $star }}" class="peer sr-only" required>
                    <span class="flex items-center justify-center w-11 h-11 rounded-full border border-corn-sage bg-white/60 text-corn-dark peer-checked:bg-corn-gold peer-checked:border-corn-gold peer-checked:text-white transition">
                        {{ $star }}
                    </span>
                </label>
            @endforeach
        </div>

        <label class="block text-sm font-medium text-corn-dark mb-1">Kritik &amp; Saran (opsional)</label>
        <textarea name="comment" rows="3" maxlength="1000"
            class="w-full border border-corn-sage bg-white/70 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-corn-green"
            placeholder="Tulis masukan Anda di sini..."></textarea>

        <button type="submit" class="w-full bg-corn-green hover:bg-corn-caramel transition text-white font-medium py-3 rounded-lg">
            Kirim Feedback
        </button>
    </form>

    <form method="POST" action="{{ route('kunjungan.skip-feedback') }}">
        @csrf
        <button type="submit" class="w-full text-corn-dark/70 text-sm py-2 hover:text-corn-dark transition">
            Lewati
        </button>
    </form>
</div>
@endsection