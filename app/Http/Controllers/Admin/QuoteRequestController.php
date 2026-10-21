<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class QuoteRequestController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.quotes.index', [
            'quotes' => static::filteredPaginated($request),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * @param  array<string>|null  $statuses  When provided, restricts to these statuses instead of query filters.
     */
    public static function applyQuoteListFilters(Builder $query, Request $request, bool $onlyNewStatuses = false, ?array $statuses = null): void
    {
        if ($statuses !== null) {
            $query->whereIn('status', $statuses);
        } elseif ($onlyNewStatuses) {
            $query->whereIn('status', ['new', 'contacted']);
        }

        $search = trim((string) $request->query('search'));
        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(function ($q) use ($like): void {
                $q->where('full_name', 'like', $like)
                    ->orWhere('company_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('county', 'like', $like)
                    ->orWhere('location', 'like', $like)
                    ->orWhere('media_type', 'like', $like);
            });
        }

        if (! $onlyNewStatuses && ! $statuses && $request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        if ($request->filled('county')) {
            $query->where('county', $request->query('county'));
        }

        if ($request->filled('service')) {
            $query->where('media_type', $request->query('service'));
        }

        if ($request->filled('from')) {
            try {
                $query->whereDate('created_at', '>=', Carbon::parse($request->query('from')));
            } catch (\Throwable) {
                // ignore invalid date
            }
        }

        if ($request->filled('to')) {
            try {
                $query->whereDate('created_at', '<=', Carbon::parse($request->query('to')));
            } catch (\Throwable) {
                // ignore invalid date
            }
        }
    }

    /**
     * @param  array<string>|null  $statuses  When provided, restricts to these statuses instead of query filters.
     */
    public static function filteredPaginated(Request $request, bool $onlyNewStatuses = false, ?array $statuses = null): LengthAwarePaginator
    {
        $query = QuoteRequest::query()->latest();
        static::applyQuoteListFilters($query, $request, $onlyNewStatuses, $statuses);

        return $query->paginate(20)->withQueryString();
    }

    public function show(QuoteRequest $quote): View
    {
        return view('admin.quotes.show', [
            'quote' => $quote->load('assignee'),
            'users' => User::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function update(Request $request, QuoteRequest $quote): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['nullable', 'string', 'max:50'],
            'internal_notes' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'exists:users,id'],
        ]);

        $quote->update([
            'status' => $validated['status'] ?? $quote->status,
            'internal_notes' => $validated['internal_notes'] ?? $quote->internal_notes,
            'assigned_to' => $validated['assigned_to'] ?? $quote->assigned_to,
        ]);

        return redirect()->route('admin.quotes.show', $quote)->with('status', 'Quote request updated.');
    }

    public function destroy(QuoteRequest $quote): RedirectResponse
    {
        $quote->delete();

        return redirect()
            ->route('admin.quotes.index')
            ->with('status', 'Quote request deleted.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'quote-requests-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($request): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, [
                'id',
                'full_name',
                'company_name',
                'phone',
                'email',
                'county',
                'location',
                'industry',
                'campaign_objective',
                'target_audience',
                'media_type',
                'campaign_duration',
                'budget_range',
                'campaign_slug',
                'message',
                'source',
                'status',
                'internal_notes',
                'assigned_to',
                'created_at',
            ]);

            $query = QuoteRequest::query()->latest('id');
            static::applyQuoteListFilters($query, $request, false, null);

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
                        $quote->industry,
                        $quote->campaign_objective,
                        $quote->target_audience,
                        $quote->media_type,
                        $quote->campaign_duration,
                        $quote->budget_range,
                        $quote->campaign_slug,
                        $quote->message,
                        $quote->source,
                        $quote->status,
                        $quote->internal_notes,
                        $quote->assigned_to,
                        optional($quote->created_at)->toDateTimeString(),
                    ]);
                }
            });

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
