<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileDownloadRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileDownloadRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = ProfileDownloadRequest::query()->latest();

        $state = $request->query('state', 'active');
        if ($state === 'archived') {
            $query->archived();
        } elseif ($state === 'active') {
            $query->active();
        }

        $search = trim((string) $request->query('search', ''));
        if ($search !== '') {
            $like = '%'.$search.'%';
            $query->where(function ($q) use ($like): void {
                $q->where('full_name', 'like', $like)
                    ->orWhere('company_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('filename', 'like', $like);
            });
        }

        if ($request->filled('document')) {
            $query->where('filename', $request->query('document'));
        }

        if ($request->filled('from')) {
            try {
                $query->whereDate('created_at', '>=', Carbon::parse($request->query('from')));
            } catch (\Throwable) {
            }
        }

        if ($request->filled('to')) {
            try {
                $query->whereDate('created_at', '<=', Carbon::parse($request->query('to')));
            } catch (\Throwable) {
            }
        }

        return view('admin.profile-downloads.index', [
            'downloads' => $query->paginate(20)->withQueryString(),
            'documents' => ProfileDownloadRequest::query()->distinct()->orderBy('filename')->pluck('filename'),
        ]);
    }

    public function show(ProfileDownloadRequest $profileDownload): View
    {
        return view('admin.profile-downloads.show', [
            'download' => $profileDownload,
        ]);
    }

    public function destroy(ProfileDownloadRequest $profileDownload): RedirectResponse
    {
        $profileDownload->delete();

        return redirect()
            ->route('admin.profile-downloads.index')
            ->with('status', 'Profile download request deleted.');
    }

    public function archive(ProfileDownloadRequest $profileDownload): RedirectResponse
    {
        $profileDownload->update(['archived_at' => now()]);

        return redirect()->back()->with('status', 'Lead archived.');
    }

    public function restore(ProfileDownloadRequest $profileDownload): RedirectResponse
    {
        $profileDownload->update(['archived_at' => null]);

        return redirect()->back()->with('status', 'Lead restored.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'profile-download-requests-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($request): void {
            $output = fopen('php://output', 'w');
            fputcsv($output, [
                'id',
                'full_name',
                'company_name',
                'phone',
                'email',
                'filename',
                'source',
                'archived_at',
                'created_at',
            ]);

            $query = ProfileDownloadRequest::query()->latest('id');

            $state = $request->query('state');
            if ($state === 'archived') {
                $query->archived();
            } elseif ($state !== 'all') {
                $query->active();
            }

            $search = trim((string) $request->query('search', ''));
            if ($search !== '') {
                $like = '%'.$search.'%';
                $query->where(function ($q) use ($like): void {
                    $q->where('full_name', 'like', $like)
                        ->orWhere('company_name', 'like', $like)
                        ->orWhere('email', 'like', $like)
                        ->orWhere('phone', 'like', $like)
                        ->orWhere('filename', 'like', $like);
                });
            }

            if ($request->filled('document')) {
                $query->where('filename', $request->query('document'));
            }

            if ($request->filled('from')) {
                try {
                    $query->whereDate('created_at', '>=', Carbon::parse($request->query('from')));
                } catch (\Throwable) {
                }
            }

            if ($request->filled('to')) {
                try {
                    $query->whereDate('created_at', '<=', Carbon::parse($request->query('to')));
                } catch (\Throwable) {
                }
            }

            $query->chunk(200, static function ($downloads) use ($output): void {
                foreach ($downloads as $download) {
                    fputcsv($output, [
                        $download->id,
                        $download->full_name,
                        $download->company_name,
                        $download->phone,
                        $download->email,
                        $download->filename,
                        $download->source,
                        optional($download->archived_at)->toDateTimeString(),
                        optional($download->created_at)->toDateTimeString(),
                    ]);
                }
            });

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
