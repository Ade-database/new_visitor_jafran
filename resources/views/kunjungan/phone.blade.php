@extends('layouts.kunjungan')
@section('title', 'Selamat Datang')
@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-xl font-bold mb-2">Selamat Datang</h1>
    <p class="text-gray-500 mb-6">Masukkan nomor HP Anda untuk memulai.</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('kunjungan.check-phone') }}">
        @csrf
        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP</label>
        <input type="tel" name="phone_number" required placeholder="08xxxxxxxxxx"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-lg">
            Lanjut
        </button>
    </form>
</div>
@endsection