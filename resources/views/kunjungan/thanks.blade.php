@extends('layouts.kunjungan')

@section('title', 'Terima Kasih')

@section('content')

<div class="fixed inset-0 -z-10 bg-cover bg-center"
     style="background-image: url('{{ asset('images/background_visitor.jpg') }}');">
</div>

<style>
    @keyframes pulse-dot {
        0%   { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6); }
        70%  { box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }
    .timer-box {
        min-width: 3.6rem;
        padding: 0.6rem 0.4rem 0.4rem;
        border-radius: 0.9rem;
        background: rgba(255, 255, 255, 0.55);
        border: 1px solid rgba(255, 255, 255, 0.7);
        box-shadow: 0 4px 12px rgba(60, 80, 30, 0.12);
    }
    .timer-num {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        color: #3b4a22;
        font-variant-numeric: tabular-nums;
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
    }
    .timer-label {
        display: block;
        margin-top: 0.3rem;
        font-size: 0.6rem;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: rgba(59, 74, 34, 0.7);
    }
    .timer-sep {
        font-size: 1.6rem;
        font-weight: 700;
        color: rgba(59, 74, 34, 0.5);
        padding-bottom: 1rem;
    }
</style>

<div class="rounded-2xl shadow-lg p-6 border border-white/50 text-center"
     style="background-color: rgba(255,255,255,0.4); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">

    <img src="{{ asset('images/logo_jafran.png') }}"
         alt="Logo Jafran"
         class="mx-auto mb-4 object-contain"
         style="width: 80px; height: auto;">

    @if (session('info'))

        <p class="text-corn-dark font-medium mb-4">
            {{ session('info') }}
        </p>

    @else

        <h1 id="status-title" class="text-xl font-bold mb-2 text-corn-dark">
            Menunggu Persetujuan
        </h1>

        <p id="status-description" class="text-corn-dark/80 text-sm mb-6">
            Mohon tunggu, data Anda sedang diproses oleh admin.
        </p>

        {{-- Timer durasi kunjungan (muncul setelah disetujui) --}}
        <div id="timer-card" class="hidden rounded-xl mb-5 px-4 py-4"
             style="background: rgba(255,255,255,0.35); border: 1px solid rgba(255,255,255,0.6);">

            <div style="display:flex; align-items:center; justify-content:center; gap:0.5rem; margin-bottom:0.9rem;">
                <span style="width:0.6rem; height:0.6rem; border-radius:9999px; background:#22c55e; animation:pulse-dot 1.8s infinite;"></span>
                <span style="font-size:0.7rem; letter-spacing:0.14em; text-transform:uppercase; font-weight:600; color:#3b4a22;">
                    Sedang berkunjung
                </span>
            </div>

            <div style="display:flex; align-items:flex-end; justify-content:center; gap:0.4rem;">
                <div class="timer-box">
                    <span id="t-hours" class="timer-num">00</span>
                    <span class="timer-label">Jam</span>
                </div>
                <span class="timer-sep">:</span>
                <div class="timer-box">
                    <span id="t-minutes" class="timer-num">00</span>
                    <span class="timer-label">Menit</span>
                </div>
                <span class="timer-sep">:</span>
                <div class="timer-box">
                    <span id="t-seconds" class="timer-num">00</span>
                    <span class="timer-label">Detik</span>
                </div>
            </div>

            <p id="timer-since" class="text-corn-dark/80 text-xs" style="margin-top:0.9rem;"></p>
        </div>

        <div id="status-badge"
             class="inline-block px-4 py-2 rounded-full bg-corn-gold/20 text-corn-dark font-medium mb-2">
            Status:
            <span id="status-text">Menunggu Persetujuan</span>
        </div>

        {{-- Instruksi checkout (hanya saat disetujui) --}}
        <p id="checkout-instruction"
           class="hidden text-corn-dark text-sm font-medium mt-4 leading-relaxed">
            Untuk checkout, buka halaman awal lalu masukkan kembali nomor HP Anda.
        </p>

        {{-- Alasan penolakan (hanya saat ditolak) --}}
        <div id="rejection-box"
             class="hidden mt-4 text-left rounded-xl border border-red-200 bg-red-50/80 p-4">
            <p class="text-xs font-semibold text-red-700 mb-1">Alasan penolakan:</p>
            <p id="rejection-reason" class="text-sm text-red-800"></p>
        </div>

        {{-- Tombol kembali (hanya saat ditolak / selesai) --}}
        <a id="retry-button"
           href="{{ route('kunjungan.phone') }}"
           class="hidden mt-5 inline-block px-5 py-2 rounded-lg text-white text-sm font-medium"
           style="background-color: #4a5a2e;">
            Kembali ke Halaman Awal
        </a>

    @endif

</div>

@if (! session('info'))

<script>
    let pollTimer = null;
    let timerInterval = null;

    const pad = (n) => String(n).padStart(2, '0');

    function startTimer(approvedAtIso, serverNowIso) {
        if (timerInterval) return; // sudah berjalan

        const startMs = new Date(approvedAtIso).getTime();
        const serverNowMs = new Date(serverNowIso).getTime();
        const loadedAt = performance.now();

        const elHours = document.getElementById('t-hours');
        const elMinutes = document.getElementById('t-minutes');
        const elSeconds = document.getElementById('t-seconds');
        const since = document.getElementById('timer-since');

        const startDate = new Date(approvedAtIso);
        since.innerHTML = 'Masuk pukul <strong>'
            + pad(startDate.getHours()) + ':' + pad(startDate.getMinutes())
            + '</strong>';

        function tick() {
            const nowMs = serverNowMs + (performance.now() - loadedAt);
            const diff = Math.max(0, Math.floor((nowMs - startMs) / 1000));

            elHours.textContent = pad(Math.floor(diff / 3600));
            elMinutes.textContent = pad(Math.floor((diff % 3600) / 60));
            elSeconds.textContent = pad(diff % 60);
        }

        tick();
        timerInterval = setInterval(tick, 1000);
    }

    function pollStatus() {

        fetch("{{ route('kunjungan.status') }}")
            .then(response => response.json())
            .then(data => {

                if (!data.status) return;

                const status = data.status;

                const title = document.getElementById('status-title');
                const description = document.getElementById('status-description');
                const statusText = document.getElementById('status-text');
                const badge = document.getElementById('status-badge');
                const timerCard = document.getElementById('timer-card');
                const checkoutInstruction = document.getElementById('checkout-instruction');
                const rejectionBox = document.getElementById('rejection-box');
                const rejectionReason = document.getElementById('rejection-reason');
                const retryButton = document.getElementById('retry-button');

                if (status === 'pending') {

                    title.textContent = 'Menunggu Persetujuan';
                    description.textContent = 'Mohon tunggu, data Anda sedang diproses oleh admin.';
                    statusText.textContent = 'Menunggu Persetujuan';
                    badge.className = 'inline-block px-4 py-2 rounded-full bg-corn-gold/20 text-corn-dark font-medium mb-2';

                    timerCard.classList.add('hidden');
                    checkoutInstruction.classList.add('hidden');
                    rejectionBox.classList.add('hidden');
                    retryButton.classList.add('hidden');

                } else if (status === 'approved') {

                    title.textContent = 'Kunjungan Disetujui';
                    description.textContent = 'Kunjungan Anda telah disetujui. Silakan melanjutkan sesuai keperluan Anda.';
                    statusText.textContent = 'Disetujui';
                    badge.className = 'inline-block px-4 py-2 rounded-full bg-corn-green/20 text-corn-dark font-medium mb-2';

                    rejectionBox.classList.add('hidden');
                    retryButton.classList.add('hidden');
                    checkoutInstruction.classList.remove('hidden');

                    if (data.approved_at) {
                        timerCard.classList.remove('hidden');
                        startTimer(data.approved_at, data.server_now);
                    }

                    // Polling tetap jalan supaya terdeteksi kalau admin checkout-kan tamu

                } else if (status === 'rejected') {

                    title.textContent = 'Kunjungan Ditolak';
                    description.textContent = 'Mohon maaf, permohonan kunjungan Anda belum dapat disetujui.';
                    statusText.textContent = 'Ditolak';
                    badge.className = 'inline-block px-4 py-2 rounded-full bg-red-100 text-red-700 font-medium mb-2';

                    timerCard.classList.add('hidden');
                    checkoutInstruction.classList.add('hidden');
                    rejectionReason.textContent = data.rejected_reason || '-';
                    rejectionBox.classList.remove('hidden');
                    retryButton.classList.remove('hidden');

                    clearInterval(pollTimer);

                } else if (status === 'checked_out') {

                    title.textContent = 'Kunjungan Selesai';
                    description.textContent = 'Terima kasih sudah berkunjung ke PT. Jafran Indonesia.';
                    statusText.textContent = 'Selesai';
                    badge.className = 'inline-block px-4 py-2 rounded-full bg-corn-green/20 text-corn-dark font-medium mb-2';

                    timerCard.classList.add('hidden');
                    checkoutInstruction.classList.add('hidden');
                    retryButton.classList.remove('hidden');

                    clearInterval(timerInterval);
                    clearInterval(pollTimer);
                }

            })
            .catch(error => {
                console.error('Gagal mengambil status:', error);
            });
    }

    // Cek status setiap 5 detik
    pollTimer = setInterval(pollStatus, 5000);

    // Cek status langsung saat halaman dibuka
    pollStatus();
</script>

@endif

@endsection