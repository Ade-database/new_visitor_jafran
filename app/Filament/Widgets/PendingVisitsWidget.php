<?php

namespace App\Filament\Widgets;

use App\Models\Visit;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class PendingVisitsWidget extends BaseWidget
{
    protected static ?string $heading = 'Kunjungan Perlu Aksi';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Visit::query()
                    ->whereIn('status', ['pending', 'approved'])
                    ->latest('submitted_at')
            )
            ->columns([
                TextColumn::make('visitor.name')
                    ->label('Nama Tamu')
                    ->searchable(),

                TextColumn::make('department.name')
                    ->label('Departemen')
                    ->placeholder('-'),

                TextColumn::make('purpose')
                    ->label('Tujuan')
                    ->limit(30),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('submitted_at')
                    ->label('Waktu Submit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('checkin')
                    ->label('Check-in')
                    ->color('info')
                    ->icon('heroicon-o-arrow-right-end-on-rectangle')
                    ->visible(fn (Visit $record) => $record->status === 'approved')
                    ->requiresConfirmation()
                    ->action(function (Visit $record) {
                        $record->checkIn();

                        Notification::make()
                            ->title('Tamu berhasil check-in')
                            ->success()
                            ->send();
                    }),

                Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-o-check')
                    ->visible(fn (Visit $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Visit $record) {
                        $record->approve(Auth::user());

                        Notification::make()
                            ->title('Kunjungan disetujui')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-mark')
                    ->visible(fn (Visit $record) => $record->status === 'pending')
                    ->schema([
                        Textarea::make('reason')
                            ->label('Alasan penolakan')
                            ->required(),
                    ])
                    ->action(function (Visit $record, array $data) {
                        $record->reject(Auth::user(), $data['reason']);

                        Notification::make()
                            ->title('Kunjungan ditolak')
                            ->danger()
                            ->send();
                    }),
            ])
            ->paginated([5])
            ->emptyStateHeading('Tidak ada kunjungan yang perlu aksi')
            ->emptyStateDescription('Semua kunjungan sudah diproses.')
            ->emptyStateIcon('heroicon-o-check-circle');
    }
}