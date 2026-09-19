@extends('layouts.kunjungan')
@section('title', 'Data Tamu Baru')
@section('content')

<div class="fixed inset-0 -z-10 bg-cover bg-center"
     style="background-image: url('{{ asset('images/background_visitor.jpg') }}');"></div>

<div class="rounded-2xl shadow-lg p-6 border border-white/50"
     style="background-color: rgba(255,255,255,0.4); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">

    <img src="{{ asset('images/logo_jafran.png') }}" alt="Logo Jafran"
         class="mx-auto mb-4 object-contain"
         style="width: 80px; height: auto;">

    <h1 class="text-xl font-bold mb-1 text-corn-dark text-center">Data Tamu Baru</h1>
    <p class="text-corn-dark/80 text-sm mb-6 text-center">Nomor Anda belum terdaftar, silakan lengkapi data berikut.</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('kunjungan.store-new-visitor') }}" enctype="multipart/form-data">
        @csrf

        <label class="block text-sm font-medium text-corn-dark mb-1">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name') }}" required
            class="w-full border border-corn-sage bg-white/70 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-corn-green">

        <label class="block text-sm font-medium text-corn-dark mb-1">Perusahaan/Instansi</label>
        <input type="text" name="company" value="{{ old('company') }}"
            class="w-full border border-corn-sage bg-white/70 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-corn-green">

        <label class="block text-sm font-medium text-corn-dark mb-1">Keperluan</label>
        <input type="text" name="purpose" value="{{ old('purpose') }}" required
            placeholder="Contoh: Meeting, delivery, interview"
            class="w-full border border-corn-sage bg-white/70 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-corn-green">

        <label class="block text-sm font-medium text-corn-dark mb-1">Departemen/Tujuan</label>
        <select name="department_id" required
            class="w-full border border-corn-sage bg-white/70 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-corn-green">
            <option value="">-- Pilih departemen --</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>

        <label class="block text-sm font-medium text-corn-dark mb-1">Foto Diri</label>
        <input type="file" name="photo" accept="image/*" capture="user" required
            class="w-full text-sm text-corn-dark/70 mb-6 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-corn-sage file:text-corn-dark">

        <button type="submit" class="w-full bg-corn-green hover:bg-corn-caramel transition text-white font-medium py-3 rounded-lg">
            Lanjutkan
        </button>
    </form>
</div>
@endsection