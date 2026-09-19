@extends('layouts.kunjungan')
@section('title', 'Konfirmasi Kunjungan')
@section('content')

<div class="fixed inset-0 -z-10 bg-cover bg-center"
     style="background-image: url('{{ asset('images/background_visitor.jpg') }}');"></div>

<div class="rounded-2xl shadow-lg p-6 border border-white/50"
     style="background-color: rgba(255,255,255,0.4); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">

    <img src="{{ asset('images/logo_jafran.png') }}" alt="Logo Jafran"
         class="mx-auto mb-4 object-contain"
         style="width: 80px; height: auto;">

    <h1 class="text-xl font-bold mb-1 text-corn-dark text-center">Konfirmasi Kunjungan</h1>
    <p class="text-corn-dark/80 text-sm mb-6 text-center">Selamat datang kembali, {{ $visitor->name }}!</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif

    <div class="mb-4 p-4 rounded-xl text-sm text-corn-dark" style="background-color: rgba(228,233,216,0.6);">
        <p><span class="font-medium">Nama:</span> {{ $visitor->name }}</p>
        <p><span class="font-medium">No. HP:</span> {{ $visitor->phone_number }}</p>
    </div>

    <form method="POST" action="{{ route('kunjungan.store-confirm') }}">
        @csrf

        <label class="block text-sm font-medium text-corn-dark mb-1">Perusahaan/Instansi</label>
        <input type="text" name="company" value="{{ old('company', $visitor->company) }}"
            placeholder="Nama perusahaan/instansi"
            class="w-full border border-corn-sage bg-white/70 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-corn-green">

        <label class="block text-sm font-medium text-corn-dark mb-1">Keperluan kunjungan</label>
        <input type="text" name="purpose" value="{{ old('purpose') }}" required
            placeholder="Contoh: Meeting, delivery, interview"
            class="w-full border border-corn-sage bg-white/70 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-corn-green">

        <label class="block text-sm font-medium text-corn-dark mb-1">Departemen/Tujuan</label>
        <select name="department_id" required
            class="w-full border border-corn-sage bg-white/70 rounded-lg px-4 py-3 mb-6 focus:outline-none focus:ring-2 focus:ring-corn-green">
            <option value="">-- Pilih departemen --</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="w-full bg-corn-green hover:bg-corn-caramel transition text-white font-medium py-3 rounded-lg">
            Lanjutkan
        </button>
    </form>
</div>
@endsection