@extends('layouts.kunjungan')
@section('title', 'Status Kunjungan')
@section('content')
<div class="bg-white rounded-2xl shadow p-6 text-center">
    <div id="status-icon" class="text-5xl mb-4">⏳</div>
    <h1 id="status-title" class="text-xl font-bold mb-2">Menunggu Persetujuan</h1>
    <p id="status-desc" class="text-gray-500">
        Data kunjungan Anda sedang ditinjau oleh admin. Silakan tunggu di lobby.
    </p>

    @if (session('info'))
        <p class="text-sm text-amber-600 mt-4">{{ session('info') }}</p>
    @endif
</div>

@if ($visit)
<script>
    const statusMap = {
        pending: { icon: '⏳', title: 'Menunggu Persetujuan', desc: 'Data kunjungan Anda sedang ditinjau oleh admin. Silakan tunggu di lobby.' },
        approved: { icon: '✅', title: 'Disetujui', desc: 'Kunjungan Anda disetujui. Silakan menuju resepsionis untuk check-in.' },
        rejected: { icon: '❌', title: 'Ditolak', desc: 'Maaf, kunjungan Anda tidak dapat diproses saat ini.' },
        checked_in: { icon: '🚶', title: 'Sedang Berkunjung', desc: 'Selamat berkunjung! Untuk pulang, scan QR yang sama lagi ya.' },
        checked_out: { icon: '👋', title: 'Kunjungan Selesai', desc: 'Terima kasih sudah berkunjung. Sampai jumpa lagi!' },
    };

    function applyStatus(status) {
        const info = statusMap[status] ?? statusMap.pending;
        document.getElementById('status-icon').textContent = info.icon;
        document.getElementById('status-title').textContent = info.title;
        document.getElementById('status-desc').textContent = info.desc;
    }

    applyStatus(@json($visit->status));

    const pollInterval = setInterval(async () => {
        try {
            const res = await fetch('{{ route('kunjungan.status') }}');
            const data = await res.json();

            if (!data.status) return;

            applyStatus(data.status);

            // Kalau statusnya udah final, nggak perlu nanya-nanya lagi ke server
            if (['rejected', 'checked_out'].includes(data.status)) {
                clearInterval(pollInterval);
            }
        } catch (e) {
            // Kalau lagi nggak ada koneksi internet, diem aja, coba lagi nanti
        }
    }, 5000);
</script>
@endif
@endsection