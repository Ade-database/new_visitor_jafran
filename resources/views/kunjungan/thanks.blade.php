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

        {{-- Judul --}}
        <h1 id="status-title"
            class="text-xl font-bold mb-2 text-corn-dark">
            Menunggu Persetujuan
        </h1>

        {{-- Deskripsi --}}
        <p id="status-description"
           class="text-corn-dark/80 text-sm mb-6">
            Mohon tunggu, data Anda sedang diproses oleh admin.
        </p>

        {{-- Status Badge --}}
        <div id="status-badge"
             class="inline-block px-4 py-2 rounded-full bg-corn-gold/20 text-corn-dark font-medium mb-2">

            Status:
            <span id="status-text">pending</span>

        </div>

        {{-- Instruksi Checkout --}}
        <p id="checkout-instruction"
           class="hidden text-corn-dark text-sm font-medium mt-5 leading-relaxed">

            Untuk melakukan checkout, silakan scan barcode yang sama
            atau scan ulang barcode di pos satpam.

        </p>

    @endif

</div>

@if (! session('info'))

<script>
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
                const checkoutInstruction = document.getElementById('checkout-instruction');

                // Pastikan instruksi checkout disembunyikan
                // selama belum checked_in
                checkoutInstruction.classList.add('hidden');

                // ==========================
                // STATUS PENDING
                // ==========================
                if (status === 'pending') {

                    title.textContent = 'Menunggu Persetujuan';

                    description.textContent =
                        'Mohon tunggu, data Anda sedang diproses oleh admin.';

                    statusText.textContent = 'Menunggu Persetujuan';

                    badge.className =
                        'inline-block px-4 py-2 rounded-full bg-corn-gold/20 text-corn-dark font-medium mb-2';
                }

                // ==========================
                // STATUS APPROVED
                // ==========================
                else if (status === 'approved') {

                    title.textContent = 'Kunjungan Disetujui';

                    description.textContent =
                        'Kunjungan Anda telah disetujui. Silakan melanjutkan sesuai keperluan Anda.';

                    statusText.textContent = 'Disetujui';

                    badge.className =
                        'inline-block px-4 py-2 rounded-full bg-corn-green/20 text-corn-dark font-medium mb-2';
                }

                // ==========================
                // STATUS CHECKED IN
                // ==========================
                else if (status === 'checked_in') {

                    title.textContent = 'Anda Sedang Berkunjung';

                    description.textContent =
                        'Selamat datang di PT. Jafran Indonesia. Kunjungan Anda telah berhasil dicatat.';

                    statusText.textContent = 'Sedang Berkunjung';

                    badge.className =
                        'inline-block px-4 py-2 rounded-full bg-corn-green/20 text-corn-dark font-medium mb-2';

                    // Tampilkan instruksi checkout
                    checkoutInstruction.classList.remove('hidden');

                }

                // ==========================
                // STATUS REJECTED
                // ==========================
                else if (status === 'rejected') {

                    title.textContent = 'Kunjungan Ditolak';

                    description.textContent =
                        'Mohon maaf, permohonan kunjungan Anda belum dapat disetujui.';

                    statusText.textContent = 'Ditolak';

                    badge.className =
                        'inline-block px-4 py-2 rounded-full bg-red-100 text-red-700 font-medium mb-2';
                }

            })
            .catch(error => {

                console.error('Gagal mengambil status:', error);

            });
    }

    // Cek status setiap 5 detik
    setInterval(pollStatus, 5000);

    // Cek status langsung saat halaman dibuka
    pollStatus();
</script>

@endif

@endsection