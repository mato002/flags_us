<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PlaceholderController extends Controller
{
    /** @var array<string, array{title: string, description: string}> */
    private array $definitions = [
        'counties-towns' => [
            'title' => 'Counties & towns',
            'description' => 'Normalize coverage geography from coverage sites. Bulk tools will land here.',
        ],
        'map-data' => [
            'title' => 'Map data',
            'description' => 'Import GEOJSON and manage map layers for the public coverage explorer.',
        ],
        'invoices' => [
            'title' => 'Invoices',
            'description' => 'Finance-ready invoice records will be connected to quote conversions.',
        ],
        'receipts' => [
            'title' => 'Receipts',
            'description' => 'Payment receipts and reconciliation views.',
        ],
        'campaigns' => [
            'title' => 'Campaigns',
            'description' => 'Active field campaigns, timelines, and linkage to assets.',
        ],
        'client-work' => [
            'title' => 'Client work',
            'description' => 'Curated deliverables and approvals separate from the public portfolio.',
        ],
        'maintenance-logs' => [
            'title' => 'Maintenance logs',
            'description' => 'Site visits, cleaning cycles, and fault tickets for inventory.',
        ],
        'settings' => [
            'title' => 'Portal settings',
            'description' => 'Branding, notifications, and integration keys for the operations portal.',
        ],
    ];

    public function show(string $module): View
    {
        abort_unless(isset($this->definitions[$module]), 404);
        $meta = $this->definitions[$module];

        return view('admin.placeholder', [
            'title' => $meta['title'],
            'description' => $meta['description'],
            'moduleKey' => $module,
        ]);
    }
}
