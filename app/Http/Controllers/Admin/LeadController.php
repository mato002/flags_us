<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.leads.index', [
            'quotes' => QuoteRequestController::filteredPaginated($request, onlyNewStatuses: true),
            'users' => \App\Models\User::query()->orderBy('name')->get(['id', 'name']),
            'pageTitle' => 'Open quote leads',
            'pageSubtitle' => 'Requests marked as new or contacted — refine status on each record.',
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'open-quote-leads-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($request): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, [
                'id', 'full_name', 'company_name', 'phone', 'email', 'county', 'location',
                'media_type', 'budget_range', 'status', 'source', 'created_at',
            ]);

            $query = QuoteRequest::query()->latest('id');
            QuoteRequestController::applyQuoteListFilters($query, $request, onlyNewStatuses: true, statuses: null);

            $query->chunk(200, static function ($quotes) use ($output): void {
                foreach ($quotes as $quote) {
                    fputcsv($output, [
                        $quote->id,
                        $quote->full_name,
                        $quote->company_name,
                        $quote->phone,
                        $quote->email,
                        $quote->county,
                        $quote->location,
                        $quote->media_type,
                        $quote->budget_range,
                        $quote->status,
                        $quote->source,
                        optional($quote->created_at)->toDateTimeString(),
                    ]);
                }
            });

            fclose($output);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
