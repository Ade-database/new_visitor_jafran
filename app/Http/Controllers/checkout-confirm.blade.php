@extends('layouts.kunjungan')
@section('title', 'Checkout')
@section('content')
<div class="bg-white rounded-2xl shadow p-6 text-center">
    <h1 class="text-xl font-bold mb-2">Selesai kunjungan, {{ $visit->visitor->name }}?</h1>
    <p class="text-gray-500 mb-6">
        Anda check-in pada {{ $visit->checked_in_at->format('d M Y H:i') }}.
        Klik tombol di bawah untuk mengakhiri kunjungan Anda.
    </p>

    <form method="POST" action="{{ route('kunjungan.store-checkout') }}">
        @csrf
        <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-lg">
            Checkout Sekarang
        </button>
    </form>
</div>

<script>
    const pollInterval = setInterval(async () => {
        try {
            const res = await fetch('{{ route('kunjungan.checkout-status') }}');
            const data = await res.json();

            if (!data.status) return;

            // Kalau admin sudah checkout-in dari dashboard, statusnya bukan 'checked_in' lagi
            if (data.status !== 'checked_in') {
                clearInterval(pollInterval);
                window.location.href = '{{ route('kunjungan.checkout-confirm') }}';
            }
        } catch (e) {
            // Kalau lagi nggak ada koneksi internet, diem aja, coba lagi nanti
        }
    }, 5000);
</script>
@endsection