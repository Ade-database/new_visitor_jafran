@extends('layouts.kunjungan')
@section('title', 'Beri Penilaian')
@section('content')
<div class="bg-white rounded-2xl shadow p-6">
    <h1 class="text-xl font-bold mb-2">Bagaimana kunjungan Anda?</h1>
    <p class="text-gray-500 mb-6">Feedback ini opsional, tapi sangat membantu kami.</p>

    @if ($errors->any())
        <div class="mb-4 text-sm text-red-600">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('kunjungan.store-feedback') }}" class="space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Beri Rating</label>
            <div class="flex justify-between gap-2">
                @foreach ([1, 2, 3, 4, 5] as $value)
                    <label class="flex-1">
                        <input type="radio" name="rating" value="{{ $value }}" class="peer sr-only" required>
                        <div class="text-center py-3 rounded-lg border border-gray-300 cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600">
                            {{ $value }}
                        </div>
                    </label>
                @endforeach
            </div>
            <div class="flex justify-between text-xs text-gray-400 mt-1">
                <span>Kurang</span>
                <span>Sangat Baik</span>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kritik &amp; Saran (opsional)</label>
            <textarea name="comment" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-3"></textarea>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white font-medium py-3 rounded-lg">
            Kirim Feedback
        </button>
    </form>

    <form method="POST" action="{{ route('kunjungan.skip-feedback') }}" class="mt-3">
        @csrf
        <button type="submit" class="w-full text-gray-500 font-medium py-2">
            Lewati
        </button>
    </form>
</div>
@endsection