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

        <p class="text-sm text-gray-500">Isi salah satu di bawah ini — Departemen atau Nama Karyawan yang dituju.</p>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Ingin Bertemu dengan Departemen</label>
            <select name="department_id" id="department_id" class="w-full border border-gray-300 rounded-lg px-4 py-3 disabled:bg-gray-100">
                <option value="">-- Pilih Departemen --</option>
                @foreach ($departments as $department)
                    <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="text-center text-xs text-gray-400">— atau —</div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tidak Tahu Departemen? Cari Nama Karyawan</label>
            <select name="employee_id" id="employee_id" class="w-full border border-gray-300 rounded-lg px-4 py-3">
                <option value="">-- Pilih Nama Karyawan --</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" data-department="{{ $employee->department }}" @selected(old('employee_id') == $employee->id)>
                        {{ $employee->name }} ({{ $employee->department }})
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-gray-400 mt-1">Pilih karyawan akan otomatis mengisi Departemen di atas.</p>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const employeeSelect = document.getElementById('employee_id');
    const departmentSelect = document.getElementById('department_id');

    function syncDepartmentFromEmployee() {
        const selectedOption = employeeSelect.options[employeeSelect.selectedIndex];
        const departmentName = selectedOption ? selectedOption.getAttribute('data-department') : null;

        if (departmentName) {
            let matched = false;
            for (const option of departmentSelect.options) {
                if (option.textContent.trim() === departmentName.trim()) {
                    departmentSelect.value = option.value;
                    matched = true;
                    break;
                }
            }
            if (!matched) {
                departmentSelect.value = '';
            }
            departmentSelect.disabled = true;
        } else {
            departmentSelect.disabled = false;
        }
    }

    employeeSelect.addEventListener('change', syncDepartmentFromEmployee);

    // Kalau form reload karena validasi gagal & employee sudah terpilih sebelumnya
    if (employeeSelect.value) {
        syncDepartmentFromEmployee();
    }
});
</script>
@endsection