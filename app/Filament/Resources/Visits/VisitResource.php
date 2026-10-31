<?php

namespace App\Filament\Resources\Visits;

use App\Filament\Resources\Visits\Pages\ListVisits;
use App\Models\Visit;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class VisitResource extends Resource
{
    protected static ?string $model = Visit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'Kunjungan';

    public static function getNavigationBadge(): ?string
    {
        $count = Visit::where('status', 'pending')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('visitor.name')
                    ->label('Nama Tamu')
                    ->searchable(),

                TextColumn::make('visitor.category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'prioritas' ? 'warning' : 'gray'),

                TextColumn::make('department.name')
                    ->label('Departemen')
                    ->placeholder('-'),

                TextColumn::make('employee.name')
                    ->label('Karyawan Dituju')
                    ->placeholder('-'),

                TextColumn::make('purpose')
                    ->label('Tujuan')
                    ->limit(30),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'checked_out' => 'info',
                        default => 'gray',
                    }),

                TextColumn::make('submitted_at')
                    ->label('Waktu Submit')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('approved_at')
                    ->label('Waktu Disetujui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('checked_out_at')
                    ->label('Waktu Check-out')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('rejected_reason')
                    ->label('Alasan Penolakan')
                    ->limit(40)
                    ->tooltip(fn (Visit $record) => $record->rejected_reason)
                    ->placeholder('-'),

                TextColumn::make('admin.name')
                    ->label('Diproses Oleh')
                    ->placeholder('-'),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Disetujui (belum checkout)',
                        'checked_out' => 'Sudah Checkout',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
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

                Action::make('checkout')
                    ->label('Checkout')
                    ->color('gray')
                    ->icon('heroicon-o-arrow-left-start-on-rectangle')
                    ->visible(fn (Visit $record) => $record->status === 'approved')
                    ->requiresConfirmation()
                    ->modalDescription('Tandai tamu ini sudah pulang (misal tamu lupa checkout).')
                    ->action(function (Visit $record) {
                        $record->checkOut();

                        Notification::make()
                            ->title('Tamu berhasil checkout')
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVisits::route('/'),
        ];
    }
}