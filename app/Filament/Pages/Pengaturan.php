<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class Pengaturan extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Pengaturan';

    protected static ?string $slug = 'pengaturan';

    // Angka besar supaya selalu paling bawah di sidebar
    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.pengaturan';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ubah Kata Sandi')
                    ->description('Gunakan kata sandi yang kuat dan jangan dibagikan ke orang lain.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Kata Sandi Lama')
                            ->password()
                            ->revealable()
                            ->required()
                            ->currentPassword()
                            ->validationMessages([
                                'current_password' => 'Kata sandi lama tidak sesuai.',
                            ]),

                        TextInput::make('password')
                            ->label('Kata Sandi Baru')
                            ->password()
                            ->revealable()
                            ->required()
                            ->minLength(8)
                            ->different('current_password')
                            ->confirmed()
                            ->validationMessages([
                                'min'       => 'Kata sandi baru minimal 8 karakter.',
                                'different' => 'Kata sandi baru harus berbeda dari yang lama.',
                                'confirmed' => 'Konfirmasi kata sandi tidak cocok.',
                            ]),

                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Kata Sandi Baru')
                            ->password()
                            ->revealable()
                            ->required(),
                    ])
                    ->columns(1),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = Auth::user();

        // Kalau model User punya cast 'hashed' (default Laravel 11/12), cukup isi plain text
        $user->update([
            'password' => $data['password'],
        ]);

        // Supaya sesi saat ini tidak ikut logout (karena ada AuthenticateSession)
        if (request()->hasSession()) {
            request()->session()->put([
                'password_hash_' . Filament::getAuthGuard() => $user->getAuthPassword(),
            ]);
        }

        $this->form->fill();

        Notification::make()
            ->title('Kata sandi berhasil diubah')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Kata Sandi')
                ->submit('save'),
        ];
    }
}