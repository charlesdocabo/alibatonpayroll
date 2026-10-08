<?php

namespace App\Http\Controllers;

use App\Services\GeminiAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AnalyticsController extends Controller
{
    private string $gateway;
    private GeminiAnalyticsService $geminiService;

    public function __construct(GeminiAnalyticsService $geminiService)
    {
        $this->gateway = config('services.gateway.url', 'http://payroll-gateway:8000');
        $this->geminiService = $geminiService;
    }

    public function index()
    {
        $analytics = $this->fetchAnalyticsData();

        $totalEmployees = (int) ($analytics['total_employees'] ?? 0);
        $activeEmployees = (int) ($analytics['active_employees'] ?? 0);
        $inactiveEmployees = (int) ($analytics['inactive_employees'] ?? 0);
        $totalPayroll = (float) ($analytics['total_payroll'] ?? 0);
        $averageSalary = (float) ($analytics['average_salary'] ?? 0);
        $payrollRecords = (int) ($analytics['payroll_records'] ?? 0);
        $totalBenefits = (int) ($analytics['total_benefits'] ?? 0);
        $totalClaims = (int) ($analytics['total_claims'] ?? 0);

        $activePercentage = $totalEmployees > 0
            ? ($activeEmployees / $totalEmployees) * 100
            : 0;

        $inactivePercentage = $totalEmployees > 0
            ? ($inactiveEmployees / $totalEmployees) * 100
            : 0;

        // Generate or retrieve cached Gemini AI Executive Briefing
        $aiExecutiveBrief = $this->geminiService->generateExecutiveReport($analytics);
        $aiProviderInfo = $this->geminiService->getProviderInfo();

        return view('analytics.index', compact(
            'analytics',
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'totalPayroll',
            'averageSalary',
            'payrollRecords',
            'totalBenefits',
            'totalClaims',
            'activePercentage',
            'inactivePercentage',
            'aiExecutiveBrief',
            'aiProviderInfo'
        ));
    }

    /**
     * AJAX endpoint: Ask Gemini 2.5 Flash interactive question with live dataset context.
     */
    public function askGemini(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:1000',
        ]);

        $analytics = $this->fetchAnalyticsData();
        $history = $request->input('history', []);

        $result = $this->geminiService->askGemini($request->input('question'), $analytics, $history);

        return response()->json([
            'success' => true,
            'answer' => $result['text'],
            'model' => $result['model'],
            'provider' => $result['provider'],
            'status' => $result['status'],
        ]);
    }

    /**
     * AJAX endpoint: Regenerate fresh AI Executive Report.
     */
    public function refreshReport(Request $request)
    {
        $analytics = $this->fetchAnalyticsData();
        $aiExecutiveBrief = $this->geminiService->generateExecutiveReport($analytics, true);

        return response()->json([
            'success' => true,
            'brief' => $aiExecutiveBrief,
        ]);
    }

    /**
     * AJAX endpoint: Save custom Google Gemini or OpenRouter API key to current session.
     */
    public function configureKey(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string|max:200',
            'type' => 'required|in:gemini,openrouter',
        ]);

        $key = trim($request->input('api_key'));
        $type = $request->input('type');

        if ($type === 'gemini') {
            session(['custom_gemini_api_key' => $key]);
        } else {
            session(['custom_openrouter_api_key' => $key]);
        }

        // Clear cached report so fresh one is generated with new key
        \Illuminate\Support\Facades\Cache::forget('alibaton_gemini_executive_report_v2');

        return response()->json([
            'success' => true,
            'message' => 'API Key updated successfully! Gemini engine reloaded.',
        ]);
    }

    /**
     * Fetch aggregated analytics metrics from API Gateway / Analytics Service.
     */
    private function fetchAnalyticsData(): array
    {
        try {
            $response = Http::timeout(8)->get($this->gateway . '/api/analytics');
            if ($response->successful()) {
                return $response->json('data', []);
            }
        } catch (\Exception $e) {
            // Log connection warning and fallback to empty dataset
        }

        return [];
    }
}
