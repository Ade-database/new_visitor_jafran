<x-filament-widgets::widget>
    <x-filament::section style="max-width: 320px;">
        <div style="display: flex; align-items: center; gap: 1rem;">
            <x-filament::avatar
                src="{{ filament()->getUserAvatarUrl(auth()->user()) }}"
                alt="{{ auth()->user()->name }}"
                size="md"
            />
            <div>
                <p style="font-size: 0.875rem; font-weight: 600; margin: 0;">
                    Welcome
                </p>
                <p style="font-size: 0.8125rem; color: #6b7280; margin: 0;">
                    {{ auth()->user()->name }}
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>