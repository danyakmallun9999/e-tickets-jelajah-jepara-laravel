<?php

namespace App\Http\Controllers;

use App\Services\ReportExportService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __construct(
        protected ReportExportService $exportService
    ) {}

    /**
     * Show export form
     */
    public function index(): View
    {
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('admin.reports.index', compact('categories'));
    }

    /**
     * Export data to CSV
     */
    public function exportCsv(Request $request)
    {
        $request->validate([
            'type' => 'required|in:places,boundaries,infrastructures,land_uses,all',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $filters = $request->only(['start_date', 'end_date', 'category_id']);
        $path = $this->exportService->exportToCsv($request->type, $filters);

        return Storage::disk('public')->download($path);
    }

    /**
     * Export HTML report (for PDF printing)
     */
    /**
     * Export HTML report (for PDF printing)
     */
    public function exportHtml(Request $request): Response
    {
        $request->validate([
            'type' => 'nullable|in:places,boundaries,infrastructures,land_uses,all',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $filters = $request->only(['start_date', 'end_date', 'category_id']);
        $type = $request->input('type', 'all');

        $html = $this->exportService->generateHtmlReport($type, $filters);

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=utf-8',
        ]);
    }
}
