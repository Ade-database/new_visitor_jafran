@extends('layouts.kunjungan')
@section('title', 'Konfirmasi Kunjungan')
@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-xl font-bold mb-1">Konfirmasi Kunjungan</h1>
    <p class="text-gray-500 text-sm mb-6">Selamat datang kembali, {{ $visitor->name }}!</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif

    <div class="mb-6 p-4 bg-gray-50 rounded-xl text-sm text-gray-600">
        <p><span class="font-medium text-gray-800">Nama:</span> {{ $visitor->name }}</p>
        <p><span class="font-medium text-gray-800">No. HP:</span> {{ $visitor->phone_number }}</p>
        @if ($visitor->company)
            <p><span class="font-medium text-gray-800">Perusahaan:</span> {{ $visitor->company }}</p>
        @endif
    </div>

    <form method="POST" action="{{ route('kunjungan.store-confirm') }}">
        @csrf

        <label class="block text-sm font-medium text-gray-700 mb-1">Keperluan kunjungan</label>
        <input type="text" name="purpose" value="{{ old('purpose') }}" required
            placeholder="Contoh: Meeting, delivery, interview"
            class="w-full border border-gray-300 rounded-lg px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-blue-500">

        <label class="block text-sm font-medium text-gray-700 mb-1">Departemen/Tujuan</label>
        <select name="department_id" required
            class="w-full border border-gray-300 rounded-lg px-4 py-3 mb-6 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih departemen --</option>
            @foreach ($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-lg">
            Lanjutkan
        </button>
    </form>
</div>
@endsection