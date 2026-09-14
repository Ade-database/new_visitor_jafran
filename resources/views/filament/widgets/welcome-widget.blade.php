<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-black text-white flex items-center justify-center font-bold">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-base">
                        Welcome, {{ auth()->user()->name }}
                    </p>
                    <p class="text-sm text-gray-500">
                        {{ auth()->user()->role === 'admin' ? 'Admin HRD' : 'Satpam' }}
                        · {{ now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ filament()->getLogoutUrl() }}">
                @csrf
                <x-filament::button color="primary" icon="heroicon-o-arrow-left-on-rectangle" type="submit">
                    Sign out
                </x-filament::button>
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>