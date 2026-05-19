<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('app.golang_api_url', 'http://localhost:8080');
    }

    private function apiGet(string $path): array
    {
        try {
            $r = Http::timeout(10)->withToken(session('token'))->get("{$this->apiUrl}{$path}");
            return $r->successful() ? ($r->json('data') ?? $r->json() ?? []) : [];
        } catch (\Exception $e) {
            Log::warning("API GET {$path} failed: " . $e->getMessage());
            return [];
        }
    }

    public function index(Request $request)
    {
        $startDate = $request->query('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->query('end_date', now()->format('Y-m-d'));

        // Fetch reports
        $revenue = $this->apiGet("/api/admin/reports/revenue?start_date={$startDate}&end_date={$endDate}");
        $topProducts = $this->apiGet("/api/admin/reports/products?limit=10");
        $auditLogs = $this->apiGet("/api/admin/logs/audit?limit=50");
        $loginLogs = $this->apiGet("/api/admin/logs/login?limit=50");
        $productionLogs = $this->apiGet("/api/admin/logs/production?limit=50");

        return view('manager.laporan', compact(
            'revenue',
            'topProducts',
            'auditLogs',
            'loginLogs',
            'productionLogs',
            'startDate',
            'endDate'
        ));
    }
}