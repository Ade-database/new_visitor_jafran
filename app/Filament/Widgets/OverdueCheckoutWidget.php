<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class OverdueCheckoutWidget extends BaseWidget
{
    protected static ?string $heading = 'Tamu Belum Checkout';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return now()->hour >= 16;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Visit::query()
                    ->where('status', 'checked_in')
                    ->latest('checked_in_at')
            )
            ->columns([
                TextColumn::make('visitor.name')
                    ->label('Nama Tamu')
                    ->searchable(),

                TextColumn::make('department.name')
                    ->label('Departemen')
                    ->placeholder('-'),

                TextColumn::make('employee.name')
                    ->label('Karyawan Dituju')
                    ->placeholder('-'),

                TextColumn::make('checked_in_at')
                    ->label('Waktu Check-in')
                    ->dateTime('d M Y H:i'),

                TextColumn::make('last_confirmed_present_at')
                    ->label('Terakhir Dikonfirmasi')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum pernah dikonfirmasi'),
            ])
            ->recordActions([
                Action::make('confirm_present')
                    ->label('Masih di Lokasi')
                    ->color('warning')
                    ->icon('heroicon-o-user')
                    ->requiresConfirmation()
                    ->modalDescription('Konfirmasi bahwa tamu ini memang masih berada di lokasi.')
                    ->action(function (Visit $record) {
                        $record->confirmStillPresent();

                        Notification::make()
                            ->title('Dicatat: tamu masih di lokasi')
                            ->success()
                            ->send();
                    }),

                Action::make('checkout')
                    ->label('Checkout')
                    ->color('gray')
                    ->icon('heroicon-o-arrow-left-start-on-rectangle')
                    ->requiresConfirmation()
                    ->modalDescription('Tandai tamu ini sudah checkout (misal ternyata sudah pulang tapi lupa checkout).')
                    ->action(function (Visit $record) {
                        $record->checkOut();

                        Notification::make()
                            ->title('Tamu berhasil di-checkout')
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('Semua tamu sudah checkout')
            ->emptyStateDescription('Tidak ada tamu yang masih tercatat check-in.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}