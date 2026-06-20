<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BiReportService;
use Illuminate\Http\Response;

class BiReportExportController extends Controller
{
    public function __invoke(string $report, BiReportService $service): Response
    {
        $data = $service->report($report);
        $csv = $service->toCsv($data['rows']);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $data['filename'] . '"',
        ]);
    }
}
