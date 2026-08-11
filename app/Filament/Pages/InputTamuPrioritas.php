<?php

namespace App\Filament\Pages;

use App\Models\Department;
use App\Models\Visit;
use App\Models\Visitor;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class InputTamuPrioritas extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Input Tamu Prioritas';

    protected static ?string $title = 'Input Tamu Prioritas';

    protected string $view = 'filament.pages.input-tamu-prioritas';

    public ?array $data = [];

    // Cuma satpam & admin yang boleh buka halaman ini
    public static function canAccess(): bool
    {
        return in_array(Auth::user()?->role, ['satpam', 'admin']);
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Tamu')
                    ->required(),

                FileUpload::make('photo_path')
                    ->label('Foto Tamu')
                    ->image()
                    ->required()
                    ->directory('visitor-photos'),

                TextInput::make('company')
                    ->label('Instansi (opsional)'),

                TextInput::make('purpose')
                    ->label('Tujuan Kunjungan (opsional)'),

                Select::make('department_id')
                    ->label('Ingin Bertemu dengan Departemen (opsional)')
                    ->options(fn () => Department::pluck('name', 'id'))
                    ->searchable(),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $visitor = Visitor::create([
            'name'       => $data['name'],
            'company'    => $data['company'] ?? null,
            'category'   => 'prioritas',
            'photo_path' => $data['photo_path'],
        ]);

        Visit::create([
            'visitor_id'    => $visitor->id,
            'department_id' => $data['department_id'] ?? null,
            'input_by'      => Auth::id(),
            'purpose'       => $data['purpose'] ?? null,
            'status'        => 'pending',
            'submitted_at'  => now(),
        ]);

        Notification::make()
            ->title('Tamu prioritas berhasil disimpan')
            ->success()
            ->send();

        $this->form->fill(); // kosongin lagi formnya, siap input tamu berikutnya
    }
}