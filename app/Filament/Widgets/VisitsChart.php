<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;

class VisitsChart extends ChartWidget
{
    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Kunjungan 7 Hari Terakhir';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn ($i) => now()->subDays($i));

        $counts = $days->map(
            fn ($day) => Visit::whereDate('submitted_at', $day->toDateString())->count()
        );

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Kunjungan',
                    'data' => $counts->toArray(),
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.2)',
                    'fill' => true,
                ],
            ],
            'labels' => $days->map(fn ($day) => $day->translatedFormat('d M'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}