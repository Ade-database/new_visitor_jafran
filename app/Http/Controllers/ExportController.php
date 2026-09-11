<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    public function visitors(): StreamedResponse
    {
        $filename = 'data-tamu-'.now()->format('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () {
            $handle = fopen('php://output', 'w');

            // Baris judul kolom
            fputcsv($handle, [
                'Nama', 'No. HP', 'Instansi', 'Kategori',
                'Jumlah Kunjungan', 'Terdaftar Sejak',
            ]);

            Visitor::withCount('visits')->orderBy('created_at')->chunk(200, function ($visitors) use ($handle) {
                foreach ($visitors as $visitor) {
                    fputcsv($handle, [
                        $visitor->name,
                        $visitor->phone_number ?? '-',
                        $visitor->company ?? '-',
                        $visitor->category,
                        $visitor->visits_count,
                        $visitor->created_at->format('d-m-Y H:i'),
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}