<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public $reportService;
    public function __construct(ReportService $reportService) {
        $this->reportService = $reportService;
    }
    public function index()
    {
        $link = $this->reportService->generateReports();

        return response()->json(['link' => $link]);
    }
}
