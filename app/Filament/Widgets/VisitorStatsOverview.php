<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use App\Models\Visitor;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class VisitorStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Tamu Hari Ini', Visit::whereDate('submitted_at', today())->count())
                ->description('Kunjungan yang didaftarkan hari ini')
                ->icon('heroicon-o-calendar')
                ->color('info'),

            Stat::make('Kunjungan Pending', Visit::where('status', 'pending')->count())
                ->description('Menunggu persetujuan admin')
                ->icon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Disetujui Hari Ini', Visit::where('status', 'approved')->whereDate('submitted_at', today())->count())
                ->description('Kunjungan yang sudah disetujui')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Total Tamu Terdaftar', Visitor::count())
                ->description('Seluruh data tamu di sistem')
                ->icon('heroicon-o-users')
                ->color('gray'),
        ];
    }
}