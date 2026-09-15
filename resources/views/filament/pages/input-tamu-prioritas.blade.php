<x-filament-panels::page>
    <form wire:submit="submit">
        {{ $this->form }}

        <div style="margin-top: 24px;">
            <x-filament::button type="submit">
                Simpan Tamu Prioritas
            </x-filament::button>
        </div>
    </form>
</x-filament-panels::page>