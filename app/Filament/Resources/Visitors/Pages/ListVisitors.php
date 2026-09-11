<?php

namespace App\Filament\Resources\Visitors\Pages;

use App\Filament\Resources\Visitors\VisitorResource;
use App\Models\Visit;
use App\Models\Visitor;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListVisitors extends ListRecords
{
    protected static string $resource = VisitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('exportCsv')
                ->label('Export CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(route('export.visitors'))
                ->openUrlInNewTab(false),

            CreateAction::make()
                ->using(function (array $data): Visitor {
                    return DB::transaction(function () use ($data) {
                        $visitor = Visitor::create([
                            'name'         => $data['name'],
                            'phone_number' => $data['phone_number'],
                            'company'      => $data['company'] ?? null,
                            'category'     => $data['category'] ?? 'reguler',
                            'photo_path'   => $data['photo_path'] ?? null,
                        ]);

                        Visit::create([
                            'visitor_id'    => $visitor->id,
                            'department_id' => $data['department_id'],
                            'purpose'       => $data['purpose'],
                            'status'        => 'pending',
                            'submitted_at'  => now(),
                            'input_by'      => Auth::id(),
                        ]);

                        return $visitor;
                    });
                }),
        ];
    }
}