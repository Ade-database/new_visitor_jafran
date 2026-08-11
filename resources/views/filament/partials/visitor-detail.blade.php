<div class="space-y-4">
    <div class="flex items-center gap-4">
        @if ($visitor->photo_path)
            <img src="{{ asset('storage/'.$visitor->photo_path) }}"
                 alt="{{ $visitor->name }}"
                 class="w-16 h-16 rounded-full object-cover">
        @endif
        <div>
            <p class="font-semibold">{{ $visitor->name }}</p>
            <p class="text-sm text-gray-500">
                {{ $visitor->phone_number ?? '-' }} &middot; {{ $visitor->company ?? '-' }}
            </p>
        </div>
    </div>

    <div>
        <h3 class="text-sm font-medium text-gray-700 mb-2">Riwayat Kunjungan</h3>
        <div class="space-y-3">
            @forelse ($visits as $visit)
                <div class="border border-gray-200 rounded-lg p-3">
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-medium">
                            {{ $visit->submitted_at?->format('d M Y, H:i') ?? '-' }}
                        </span>
                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600">
                            {{ ucfirst(str_replace('_', ' ', $visit->status)) }}
                        </span>
                    </div>
                    <div class="text-sm text-gray-600 mt-2">
                        <p><span class="text-gray-400">Tujuan:</span> {{ $visit->purpose ?? '-' }}</p>
                        <p><span class="text-gray-400">Departemen:</span> {{ $visit->department?->name ?? '-' }}</p>
                        @if ($visit->status === 'rejected' && $visit->rejected_reason)
                            <p class="text-red-500 mt-1">Alasan ditolak: {{ $visit->rejected_reason }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-400 text-center py-4">Belum ada riwayat kunjungan.</p>
            @endforelse
        </div>
    </div>
</div>