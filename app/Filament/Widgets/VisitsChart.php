<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Widgets\ChartWidget;

class VisitsChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected ?string $heading = 'Kunjungan 12 Bulan Terakhir';

    protected function getData(): array
    {
        $months = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i));

        $counts = $months->map(
            fn ($month) => Visit::whereYear('submitted_at', $month->year)
                ->whereMonth('submitted_at', $month->month)
                ->count()
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
            'labels' => $months->map(fn ($month) => $month->translatedFormat('M Y'))->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}