<?php

use App\Http\Controllers\Admin\Cms\CmsDocumentController;
use App\Http\Controllers\Admin\Cms\CmsPageController;
use App\Http\Controllers\Admin\Cms\FaqController;
use App\Http\Controllers\Admin\Cms\HomepageController;
use App\Http\Controllers\Admin\Cms\PortfolioItemController;
use App\Http\Controllers\Admin\Cms\ServiceController;
use App\Http\Controllers\Admin\Cms\StatisticController;
use App\Http\Controllers\Admin\Cms\TestimonialController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\Media\BoardInventoryController;
use App\Http\Controllers\Admin\Media\CoverageSiteController;
use App\Http\Controllers\Admin\PlaceholderController;
use App\Http\Controllers\Admin\PortalSettingsController;
use App\Http\Controllers\Admin\ProfileDownloadRequestController;
use App\Http\Controllers\Admin\QuoteRequestController;
use App\Http\Controllers\Admin\RoleAccessController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/site-credits', static function () {
    $url = config('app.site_credit_url');

    return is_string($url) && $url !== ''
        ? redirect()->away($url, 302)
        : abort(404);
})->name('site.credits');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', static function () {
    $lines = [
        'User-agent: *',
        'Allow: /',
        '',
        '# Private / utility surfaces',
        'Disallow: /admin',
        'Disallow: /dashboard',
        'Disallow: /login',
        'Disallow: /register',
        'Disallow: /password',
        'Disallow: /email',
        'Disallow: /profile',
        'Disallow: /quote/success',
        'Disallow: /company-profiles-download/',
        'Disallow: /company-profiles/download-request',
        '',
        'Sitemap: '.url('/sitemap.xml'),
        '',
    ];

    return response(implode("\n", $lines), 200, [
        'Content-Type' => 'text/plain; charset=UTF-8',
        'Cache-Control' => 'public, max-age=86400',
    ]);
})->name('robots');

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/who-we-are', [PageController::class, 'whoWeAre'])->name('who-we-are');
Route::get('/what-we-do', [PageController::class, 'whatWeDo'])->name('what-we-do');
Route::get('/services/{serviceSlug}', [PageController::class, 'serviceShowcase'])->name('services.show');
Route::get('/portfolio', [PageController::class, 'portfolio'])->name('portfolio');
Route::get('/coverage', [PageController::class, 'coverage'])->name('coverage');
Route::get('/smart-campaign-planner', [PageController::class, 'campaignPlanner'])->name('smart-campaign-planner');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/contact-us', [PageController::class, 'contactUs'])->name('contact-us');
Route::permanentRedirect('/contact', '/contact-us')->name('contact');
Route::get('/quote', [PageController::class, 'quote'])->name('quote');
Route::post('/quote', [PageController::class, 'storeQuote'])->name('quote.store');
Route::get('/quote/success', [PageController::class, 'quoteSuccess'])->name('quote.success');
Route::get('/company-profiles/{filename}', [PageController::class, 'viewCompanyProfile'])
    ->where('filename', '.*\.pdf')
    ->name('company-profile.view');
Route::get('/company-profiles-download/{filename}', [PageController::class, 'downloadCompanyProfile'])
    ->where('filename', '.*\.pdf')
    ->name('company-profile.download');
Route::post('/company-profiles/download-request', [PageController::class, 'captureProfileDownload'])->name('company-profile.capture-download');
Route::get('/brand-assets/{filename}', [PageController::class, 'viewBrandAsset'])
    ->where('filename', '.*\.(jpg|jpeg|png|webp|gif|svg)')
    ->name('brand-asset.view');
Route::get('/advertise-in/{town}', [PageController::class, 'townLanding'])
    ->defaults('type', 'advertise-in')
    ->name('landing.advertise-in');
Route::get('/street-light-ads/{town}', [PageController::class, 'townLanding'])
    ->defaults('type', 'street-light-ads')
    ->name('landing.street-light-ads');
Route::get('/billboard-advertising/{town}', [PageController::class, 'townLanding'])
    ->defaults('type', 'billboard-advertising')
    ->name('landing.billboard-advertising');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get('/dashboard', function () {
        return redirect()->route('admin.dashboard');
    })->name('dashboard');

    Route::prefix('admin')->name('admin.')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->middleware('permission:admin.dashboard.view')
            ->name('dashboard');

        Route::resource('/quotes', QuoteRequestController::class)
            ->middleware('permission:quotes.manage')
            ->only(['index', 'show', 'destroy']);
        Route::patch('/quotes/{quote}', [QuoteRequestController::class, 'update'])
            ->middleware('permission:quotes.manage')
            ->name('quotes.update');
        Route::get('/quotes-export', [QuoteRequestController::class, 'export'])
            ->middleware('permission:quotes.manage')
            ->name('quotes.export');

        Route::get('/sales/leads', [LeadController::class, 'index'])
            ->middleware('permission:quotes.leads.manage')
            ->name('sales.leads.index');
        Route::get('/sales/leads/export', [LeadController::class, 'export'])
            ->middleware('permission:quotes.leads.manage')
            ->name('sales.leads.export');

        Route::resource('/profile-downloads', ProfileDownloadRequestController::class)
            ->middleware('permission:documents.manage')
            ->parameters(['profile-downloads' => 'profileDownload'])
            ->only(['index', 'show', 'destroy']);
        Route::post('/profile-downloads/{profileDownload}/archive', [ProfileDownloadRequestController::class, 'archive'])
            ->middleware('permission:documents.manage')
            ->name('profile-downloads.archive');
        Route::post('/profile-downloads/{profileDownload}/restore', [ProfileDownloadRequestController::class, 'restore'])
            ->middleware('permission:documents.manage')
            ->name('profile-downloads.restore');
        Route::get('/profile-downloads-export', [ProfileDownloadRequestController::class, 'export'])
            ->middleware('permission:documents.manage')
            ->name('profile-downloads.export');

        Route::prefix('system')->name('system.')->group(function (): void {
            Route::middleware('permission:roles.view')->group(function (): void {
                Route::get('/roles/matrix', [RoleAccessController::class, 'matrix'])
                    ->name('roles.matrix');
                Route::get('/roles/access-activity', [RoleAccessController::class, 'activity'])
                    ->name('roles.activity');
                Route::get('/roles/export', [RoleAccessController::class, 'export'])
                    ->name('roles.export');
                Route::get('/roles', [RoleAccessController::class, 'index'])
                    ->name('roles.index');
            });

            Route::middleware('permission:roles.manage')->group(function (): void {
                Route::get('/roles/create', [RoleAccessController::class, 'create'])
                    ->name('roles.create');
                Route::post('/roles', [RoleAccessController::class, 'store'])
                    ->name('roles.store');
                Route::get('/roles/{role}/edit', [RoleAccessController::class, 'edit'])
                    ->name('roles.edit');
                Route::patch('/roles/{role}', [RoleAccessController::class, 'update'])
                    ->name('roles.update');
                Route::delete('/roles/{role}', [RoleAccessController::class, 'destroy'])
                    ->name('roles.destroy');
                Route::post('/roles/{role}/duplicate', [RoleAccessController::class, 'duplicate'])
                    ->name('roles.duplicate');
                Route::patch('/roles/{role}/permissions', [RoleAccessController::class, 'updatePermissions'])
                    ->name('roles.permissions');
            });

            Route::middleware('permission:roles.view')->group(function (): void {
                Route::get('/roles/{role}', [RoleAccessController::class, 'show'])
                    ->name('roles.show');
            });
            Route::get('/users', [UserManagementController::class, 'index'])
                ->middleware('permission:users.manage')
                ->name('users.index');
            Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])
                ->middleware('permission:users.manage')
                ->name('users.edit');
            Route::patch('/users/{user}', [UserManagementController::class, 'update'])
                ->middleware('permission:users.manage')
                ->name('users.update');

            Route::get('/settings', [PortalSettingsController::class, 'index'])
                ->middleware('permission:settings.manage')
                ->name('settings');
            Route::put('/settings/{group}', [PortalSettingsController::class, 'update'])
                ->middleware('permission:settings.manage')
                ->where('group', '[a-z_]+')
                ->name('settings.update');
            Route::post('/settings/actions/{action}', [PortalSettingsController::class, 'action'])
                ->middleware('permission:settings.manage')
                ->where('action', '[a-z-]+')
                ->name('settings.actions');
        });

        Route::middleware('permission:media.coverage.manage')->prefix('media')->name('media.')->group(function (): void {
            Route::resource('coverage-sites', CoverageSiteController::class);
        });

        Route::middleware('permission:media.boards.manage')->prefix('media')->name('media.')->group(function (): void {
            Route::resource('boards', BoardInventoryController::class);
        });

        Route::middleware('permission:operations.modules.view')->group(function (): void {
            Route::get('/coverage/counties-towns', [PlaceholderController::class, 'show'])
                ->defaults('module', 'counties-towns')
                ->name('coverage.counties-towns');
            Route::get('/coverage/map-data', [PlaceholderController::class, 'show'])
                ->defaults('module', 'map-data')
                ->name('coverage.map-data');
        });

        Route::middleware('permission:invoices.manage')->group(function (): void {
            Route::get('/finance/invoices', [PlaceholderController::class, 'show'])
                ->defaults('module', 'invoices')
                ->name('finance.invoices');
            Route::get('/finance/receipts', [PlaceholderController::class, 'show'])
                ->defaults('module', 'receipts')
                ->name('finance.receipts');
        });

        Route::middleware('permission:campaigns.manage')->group(function (): void {
            Route::get('/operations/campaigns', [PlaceholderController::class, 'show'])
                ->defaults('module', 'campaigns')
                ->name('operations.campaigns');
            Route::get('/operations/client-work', [PlaceholderController::class, 'show'])
                ->defaults('module', 'client-work')
                ->name('operations.client-work');
            Route::get('/operations/maintenance-logs', [PlaceholderController::class, 'show'])
                ->defaults('module', 'maintenance-logs')
                ->name('operations.maintenance-logs');
        });

        Route::prefix('cms')->name('cms.')->group(function (): void {
            Route::get('/homepage', [HomepageController::class, 'edit'])
                ->middleware('permission:cms.homepage.manage')
                ->name('homepage.edit');
            Route::put('/homepage', [HomepageController::class, 'update'])
                ->middleware('permission:cms.homepage.manage')
                ->name('homepage.update');

            Route::get('/website-pages', [CmsPageController::class, 'index'])
                ->middleware('permission:cms.pages.manage')
                ->name('pages.index');
            Route::get('/website-pages/{slug}/edit', [CmsPageController::class, 'edit'])
                ->middleware('permission:cms.pages.manage')
                ->where('slug', 'home|who-we-are|what-we-do|portfolio|coverage|smart-campaign-planner|quote|faqs|contact-us')
                ->name('pages.edit');
            Route::put('/website-pages/{slug}', [CmsPageController::class, 'update'])
                ->middleware('permission:cms.pages.manage')
                ->where('slug', 'home|who-we-are|what-we-do|portfolio|coverage|smart-campaign-planner|quote|faqs|contact-us')
                ->name('pages.update');
            Route::post('/website-pages/upload-media', [CmsPageController::class, 'uploadMedia'])
                ->middleware('permission:cms.pages.manage')
                ->name('pages.upload-media');
            Route::get('/pages/who-we-are', [CmsPageController::class, 'editWhoWeAre'])
                ->middleware('permission:cms.pages.manage')
                ->name('pages.who-we-are.edit');
            Route::put('/pages/who-we-are', [CmsPageController::class, 'updateWhoWeAre'])
                ->middleware('permission:cms.pages.manage')
                ->name('pages.who-we-are.update');

            Route::post('/faqs/reorder', [FaqController::class, 'reorder'])
                ->middleware('permission:cms.faq.manage')
                ->name('faqs.reorder');
            Route::resource('/faqs', FaqController::class)
                ->middleware('permission:cms.faq.manage')
                ->except(['show']);

            Route::resource('/library', CmsDocumentController::class)
                ->middleware('permission:cms.library.manage')
                ->parameters(['library' => 'library'])
                ->except(['show']);

            Route::post('/services/reorder', [ServiceController::class, 'reorder'])
                ->middleware('permission:cms.services.manage')
                ->name('services.reorder');
            Route::resource('/services', ServiceController::class)
                ->middleware('permission:cms.services.manage')
                ->except(['show']);

            Route::resource('/portfolio', PortfolioItemController::class)
                ->middleware('permission:cms.portfolio.manage');

            Route::resource('/testimonials', TestimonialController::class)
                ->middleware('permission:cms.testimonials.manage')
                ->except(['show']);
            Route::resource('/statistics', StatisticController::class)
                ->middleware('permission:cms.statistics.manage')
                ->except(['show']);
        });
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
