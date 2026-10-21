<?php

/**
 * UI metadata for Spatie permissions — does not replace package permission names.
 * "matrix" keys describe capability coverage for the access matrix display.
 */
return [
    'modules' => [
        'website_cms' => [
            'label' => 'Website CMS',
            'permissions' => [
                'admin.dashboard.view' => [
                    'label' => 'Operations dashboard',
                    'matrix' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false, 'export' => false, 'approve' => false],
                ],
                'cms.homepage.manage' => [
                    'label' => 'Homepage',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false, 'export' => false, 'approve' => false],
                ],
                'cms.pages.manage' => [
                    'label' => 'CMS pages (e.g. Who We Are)',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false, 'export' => false, 'approve' => false],
                ],
                'cms.services.manage' => [
                    'label' => 'Services',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => false, 'approve' => false],
                ],
                'cms.portfolio.manage' => [
                    'label' => 'Portfolio & campaign assets',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => false, 'approve' => false],
                ],
                'cms.faq.manage' => [
                    'label' => 'FAQs',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => false, 'approve' => false],
                ],
                'cms.testimonials.manage' => [
                    'label' => 'Testimonials',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => false, 'approve' => false],
                ],
                'cms.statistics.manage' => [
                    'label' => 'Statistics',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => false, 'approve' => false],
                ],
                'cms.library.manage' => [
                    'label' => 'Document library',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => true, 'approve' => false],
                ],
            ],
        ],
        'media_coverage' => [
            'label' => 'Media coverage',
            'permissions' => [
                'media.coverage.manage' => [
                    'label' => 'Coverage sites',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => false, 'approve' => false],
                ],
                'media.boards.manage' => [
                    'label' => 'Board inventory',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => false, 'approve' => false],
                ],
                'operations.modules.view' => [
                    'label' => 'Coverage reference data (counties, map)',
                    'matrix' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false, 'export' => false, 'approve' => false],
                ],
                'inventory.manage' => [
                    'label' => 'Inventory operations',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false, 'export' => false, 'approve' => false],
                ],
            ],
        ],
        'sales' => [
            'label' => 'Sales',
            'permissions' => [
                'crm.manage' => [
                    'label' => 'CRM workspace',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false, 'export' => false, 'approve' => false],
                ],
                'quotes.manage' => [
                    'label' => 'Quote requests',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => true, 'approve' => true],
                ],
                'quotes.leads.manage' => [
                    'label' => 'Sales leads',
                    'matrix' => ['view' => true, 'create' => false, 'edit' => true, 'delete' => false, 'export' => true, 'approve' => false],
                ],
                'invoices.manage' => [
                    'label' => 'Invoices & receipts area',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false, 'export' => true, 'approve' => true],
                ],
            ],
        ],
        'campaign_operations' => [
            'label' => 'Campaign operations',
            'permissions' => [
                'campaigns.manage' => [
                    'label' => 'Campaigns & ops modules',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false, 'export' => false, 'approve' => true],
                ],
            ],
        ],
        'documents' => [
            'label' => 'Documents',
            'permissions' => [
                'documents.manage' => [
                    'label' => 'Lead captures & downloadable profiles',
                    'matrix' => ['view' => true, 'create' => false, 'edit' => true, 'delete' => true, 'export' => true, 'approve' => false],
                ],
            ],
        ],
        'system' => [
            'label' => 'System',
            'permissions' => [
                'reports.view' => [
                    'label' => 'Reports',
                    'matrix' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false, 'export' => true, 'approve' => false],
                ],
                'emails.manage' => [
                    'label' => 'Email tooling',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => false, 'export' => false, 'approve' => false],
                ],
                'roles.view' => [
                    'label' => 'View roles & access reports',
                    'matrix' => ['view' => true, 'create' => false, 'edit' => false, 'delete' => false, 'export' => true, 'approve' => false],
                ],
                'roles.manage' => [
                    'label' => 'Manage roles & permissions',
                    'matrix' => ['view' => true, 'create' => true, 'edit' => true, 'delete' => true, 'export' => false, 'approve' => true],
                ],
                'users.manage' => [
                    'label' => 'User accounts & assignments',
                    'matrix' => ['view' => true, 'create' => false, 'edit' => true, 'delete' => false, 'export' => false, 'approve' => true],
                ],
                'settings.manage' => [
                    'label' => 'Portal settings',
                    'matrix' => ['view' => true, 'create' => false, 'edit' => true, 'delete' => false, 'export' => false, 'approve' => true],
                ],
            ],
        ],
    ],

    /*
     * Default palette for seeded / system roles (hex).
     */
    'role_palette' => [
        'super_admin' => '#a3122a',
        'admin' => '#8b1e1a',
        'content_manager' => '#c2410c',
        'sales_staff' => '#b45309',
        'inventory_manager' => '#0d9488',
        'finance_user' => '#2563eb',
        'campaign_operations' => '#7c3aed',
        'client' => '#64748b',
    ],
];
