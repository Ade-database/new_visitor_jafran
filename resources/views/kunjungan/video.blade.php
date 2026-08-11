@extends('layouts.kunjungan')
@section('title', 'Aturan Kunjungan')
@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-xl font-bold mb-2">Aturan Kunjungan</h1>
    <p class="text-gray-500 mb-4">Mohon tonton video ini sampai selesai sebelum melanjutkan.</p>

    <video id="rules-video" class="w-full rounded-lg mb-4" controls controlsList="nodownload noplaybackrate" playsinline>
        <source src="{{ asset('videos/aturan.mp4') }}" type="video/mp4">
        Browser Anda tidak mendukung video.
    </video>

    <form method="POST" action="{{ route('kunjungan.submit') }}">
        @csrf
        <button type="submit" id="btn-lanjut" disabled
            class="w-full bg-gray-300 text-gray-500 font-medium py-3 rounded-lg cursor-not-allowed transition">
            Tonton video sampai selesai dulu ya
        </button>
    </form>
</div>

<script>
    const video = document.getElementById('rules-video');
    const btn = document.getElementById('btn-lanjut');
    let maxTimeReached = 0;

    // Kalau tamu coba tarik/geser video maju, dikembalikan ke titik terjauh yang sudah ditonton
    video.addEventListener('timeupdate', () => {
        if (video.currentTime > maxTimeReached + 0.5) {
            video.currentTime = maxTimeReached;
        } else {
            maxTimeReached = video.currentTime;
        }
    });

    // Baru aktif kalau video beneran sudah tamat
    video.addEventListener('ended', () => {
        btn.disabled = false;
        btn.textContent = 'Selesai & Kirim';
        btn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        btn.classList.add('bg-blue-600', 'text-white');
    });
</script>
@endsection