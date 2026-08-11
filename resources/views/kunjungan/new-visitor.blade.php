@extends('layouts.kunjungan')
@section('title', 'Daftar Tamu Baru')
@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-xl font-bold mb-2">Daftar Kunjungan</h1>
    <p class="text-gray-500 mb-6">Lengkapi data di bawah ini.</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600 space-y-1">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('kunjungan.store-new-visitor') }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                class="w-full border border-gray-300 rounded-lg px-4 py-3">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Instansi (opsional)</label>
            <input type="text" name="company" value="{{ old('company') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-3">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tujuan Kunjungan</label>
            <input type="text" name="purpose" value="{{ old('purpose') }}" required placeholder="Misal: Meeting, Interview"
                class="w-full border border-gray-300 rounded-lg px-4 py-3">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ingin Bertemu dengan Departemen</label>
            <select name="department_id" required class="w-full border border-gray-300 rounded-lg px-4 py-3">
                <option value="">-- Pilih Departemen --</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Foto Wajah</label>
            <input type="file" name="photo" accept="image/*" capture="user" required
                class="w-full border border-gray-300 rounded-lg px-4 py-3">
            <p class="text-xs text-gray-400 mt-1">Kamera HP akan otomatis terbuka.</p>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-lg">
            Lanjut
        </button>
    </form>
</div>
@endsection