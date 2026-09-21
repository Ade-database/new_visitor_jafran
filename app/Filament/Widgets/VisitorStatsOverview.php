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

            Stat::make('Sedang Check-in', Visit::where('status', 'checked_in')->count())
                ->description('Tamu masih berada di lokasi')
                ->icon('heroicon-o-arrow-right-end-on-rectangle')
                ->color('success'),

            Stat::make('Total Tamu Terdaftar', Visitor::count())
                ->description('Seluruh data tamu di sistem')
                ->icon('heroicon-o-users')
                ->color('gray'),
        ];
    }
}