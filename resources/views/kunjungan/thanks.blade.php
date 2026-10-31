@extends('layouts.kunjungan')

@section('title', 'Terima Kasih')

@section('content')

<div class="fixed inset-0 -z-10 bg-cover bg-center"
     style="background-image: url('{{ asset('images/background_visitor.jpg') }}');">
</div>

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

        <div id="status-badge"
             class="inline-block px-4 py-2 rounded-full bg-corn-gold/20 text-corn-dark font-medium mb-2">
            Status:
            <span id="status-text">Menunggu Persetujuan</span>
        </div>

        {{-- Alasan penolakan (hanya muncul kalau ditolak) --}}
        <div id="rejection-box"
             class="hidden mt-4 text-left rounded-xl border border-red-200 bg-red-50/80 p-4">
            <p class="text-xs font-semibold text-red-700 mb-1">Alasan penolakan:</p>
            <p id="rejection-reason" class="text-sm text-red-800"></p>
        </div>

        {{-- Tombol daftar ulang (hanya muncul kalau ditolak) --}}
        <a id="retry-button"
           href="{{ route('kunjungan.phone') }}"
           class="hidden mt-5 inline-block px-5 py-2 rounded-lg bg-corn-gold text-white text-sm font-medium">
            Kembali ke Halaman Awal
        </a>

    @endif

</div>

@if (! session('info'))

<script>
    let pollTimer = null;

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
                const rejectionBox = document.getElementById('rejection-box');
                const rejectionReason = document.getElementById('rejection-reason');
                const retryButton = document.getElementById('retry-button');

                if (status === 'pending') {

                    title.textContent = 'Menunggu Persetujuan';
                    description.textContent = 'Mohon tunggu, data Anda sedang diproses oleh admin.';
                    statusText.textContent = 'Menunggu Persetujuan';
                    badge.className = 'inline-block px-4 py-2 rounded-full bg-corn-gold/20 text-corn-dark font-medium mb-2';
                    rejectionBox.classList.add('hidden');
                    retryButton.classList.add('hidden');

                } else if (status === 'approved') {

                    title.textContent = 'Kunjungan Disetujui';
                    description.textContent = 'Kunjungan Anda telah disetujui. Silakan melanjutkan sesuai keperluan Anda.';
                    statusText.textContent = 'Disetujui';
                    badge.className = 'inline-block px-4 py-2 rounded-full bg-corn-green/20 text-corn-dark font-medium mb-2';
                    rejectionBox.classList.add('hidden');
                    retryButton.classList.add('hidden');

                    clearInterval(pollTimer);

                } else if (status === 'rejected') {

                    title.textContent = 'Kunjungan Ditolak';
                    description.textContent = 'Mohon maaf, permohonan kunjungan Anda belum dapat disetujui.';
                    statusText.textContent = 'Ditolak';
                    badge.className = 'inline-block px-4 py-2 rounded-full bg-red-100 text-red-700 font-medium mb-2';

                    rejectionReason.textContent = data.rejected_reason || '-';
                    rejectionBox.classList.remove('hidden');
                    retryButton.classList.remove('hidden');

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