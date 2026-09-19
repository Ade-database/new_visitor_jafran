@extends('layouts.kunjungan')
@section('title', 'Video Wajib Tonton')
@section('content')

<div class="fixed inset-0 -z-10 bg-cover bg-center"
     style="background-image: url('{{ asset('images/background_visitor.jpg') }}');"></div>

<div class="rounded-2xl shadow-lg p-6 border border-white/50"
     style="background-color: rgba(255,255,255,0.4); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">

    <img src="{{ asset('images/logo_jafran.png') }}" alt="Logo Jafran"
         class="mx-auto mb-4 object-contain"
         style="width: 80px; height: auto;">

    <h1 class="text-xl font-bold mb-1 text-corn-dark text-center">Video Keselamatan &amp; Tata Tertib</h1>
    <p class="text-corn-dark/80 text-sm mb-4 text-center">Mohon tonton video ini sampai selesai sebelum melanjutkan.</p>

    <video id="mandatory-video" class="w-full rounded-lg mb-4" controls controlsList="nodownload noplaybackrate"
           disablepictureinpicture src="{{ asset('videos/tata-tertib.mp4') }}">
        Browser Anda tidak mendukung pemutaran video.
    </video>

    <form method="POST" action="{{ route('kunjungan.submit') }}">
        @csrf
        <button type="submit" id="submit-btn" disabled
            class="w-full bg-corn-sage text-corn-dark/50 font-medium py-3 rounded-lg cursor-not-allowed transition">
            Tonton video sampai selesai untuk melanjutkan
        </button>
    </form>
</div>

<script>
    const video = document.getElementById('mandatory-video');
    const btn = document.getElementById('submit-btn');

    video.addEventListener('seeking', () => {
        if (video.currentTime > video.dataset.maxTime || 0) {
            video.currentTime = video.dataset.maxTime || 0;
        }
    });
    video.addEventListener('timeupdate', () => {
        video.dataset.maxTime = Math.max(video.currentTime, video.dataset.maxTime || 0);
    });
    video.addEventListener('ended', () => {
        btn.disabled = false;
        btn.textContent = 'Lanjutkan';
        btn.classList.remove('bg-corn-sage', 'text-corn-dark/50', 'cursor-not-allowed');
        btn.classList.add('bg-corn-green', 'hover:bg-corn-caramel', 'text-white');
    });
</script>
@endsection