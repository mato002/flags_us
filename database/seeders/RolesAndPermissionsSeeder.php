<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'admin.dashboard.view',
            'cms.homepage.manage',
            'cms.services.manage',
            'cms.portfolio.manage',
            'cms.testimonials.manage',
            'cms.statistics.manage',
            'cms.faq.manage',
            'cms.library.manage',
            'cms.pages.manage',
            'crm.manage',
            'quotes.manage',
            'quotes.leads.manage',
            'media.coverage.manage',
            'media.boards.manage',
            'campaigns.manage',
            'operations.modules.view',
            'invoices.manage',
            'inventory.manage',
            'documents.manage',
            'reports.view',
            'emails.manage',
            'settings.manage',
            'users.manage',
            'roles.view',
            'roles.manage',
        ];

        $permissionModels = collect();
        foreach ($permissions as $permissionName) {
            $permissionModels[$permissionName] = Permission::findOrCreate($permissionName, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $rolesToPermissions = [
            'super_admin' => $permissions,
            'admin' => [
                'admin.dashboard.view',
                'cms.homepage.manage',
                'cms.services.manage',
                'cms.portfolio.manage',
                'cms.testimonials.manage',
                'cms.statistics.manage',
                'cms.faq.manage',
                'cms.library.manage',
                'cms.pages.manage',
                'crm.manage',
                'quotes.manage',
                'quotes.leads.manage',
                'media.coverage.manage',
                'media.boards.manage',
                'campaigns.manage',
                'operations.modules.view',
                'invoices.manage',
                'inventory.manage',
                'documents.manage',
                'reports.view',
                'emails.manage',
                'roles.view',
                'roles.manage',
                'users.manage',
                'settings.manage',
            ],
            'sales_staff' => [
                'admin.dashboard.view',
                'crm.manage',
                'quotes.manage',
                'quotes.leads.manage',
                'documents.manage',
                'cms.library.manage',
                'reports.view',
                'emails.manage',
                'operations.modules.view',
            ],
            'finance_user' => [
                'admin.dashboard.view',
                'invoices.manage',
                'reports.view',
                'quotes.manage',
            ],
            'inventory_manager' => [
                'admin.dashboard.view',
                'inventory.manage',
                'media.coverage.manage',
                'media.boards.manage',
                'reports.view',
                'operations.modules.view',
            ],
            'content_manager' => [
                'admin.dashboard.view',
                'cms.homepage.manage',
                'cms.services.manage',
                'cms.portfolio.manage',
                'cms.testimonials.manage',
                'cms.statistics.manage',
                'cms.faq.manage',
                'cms.library.manage',
                'cms.pages.manage',
                'documents.manage',
                'operations.modules.view',
            ],
            'campaign_operations' => [
                'admin.dashboard.view',
                'campaigns.manage',
                'operations.modules.view',
                'cms.portfolio.manage',
                'quotes.manage',
                'quotes.leads.manage',
                'documents.manage',
                'media.coverage.manage',
                'reports.view',
            ],
            'client' => [],
        ];

        foreach ($rolesToPermissions as $roleName => $rolePermissions) {
            $role = Role::findOrCreate($roleName, 'web');
            $role->syncPermissions(
                collect($rolePermissions)
                    ->map(fn (string $permissionName) => $permissionModels[$permissionName])
                    ->all()
            );
        }

        $palette = config('access-control.role_palette', []);

        $profiles = [
            'super_admin' => [
                'display_name' => 'Super Administrator',
                'description' => 'Full platform authority including access control administration.',
                'is_system' => true,
                'badge_color' => $palette['super_admin'] ?? null,
            ],
            'admin' => [
                'display_name' => 'Administrator',
                'description' => 'Day-to-day operations leadership across CMS, media, sales, and user administration.',
                'is_system' => true,
                'badge_color' => $palette['admin'] ?? null,
            ],
            'content_manager' => [
                'display_name' => 'Content Manager',
                'description' => 'Website storytelling, FAQs, testimonials, documents, and public narrative.',
                'is_system' => true,
                'badge_color' => $palette['content_manager'] ?? null,
            ],
            'sales_staff' => [
                'display_name' => 'Sales Staff',
                'description' => 'Quotes, outbound leads, and secured profile downloads.',
                'is_system' => true,
                'badge_color' => $palette['sales_staff'] ?? null,
            ],
            'inventory_manager' => [
                'display_name' => 'Inventory Manager',
                'description' => 'Coverage sites, board inventory, and geographic reference tooling.',
                'is_system' => true,
                'badge_color' => $palette['inventory_manager'] ?? null,
            ],
            'finance_user' => [
                'display_name' => 'Finance User',
                'description' => 'Finance visibility for invoices and quote pipeline checkpoints.',
                'is_system' => true,
                'badge_color' => $palette['finance_user'] ?? null,
            ],
            'campaign_operations' => [
                'display_name' => 'Campaign Operations',
                'description' => 'Campaign delivery, collateral, and coordinating media plus sales motions.',
                'is_system' => true,
                'badge_color' => $palette['campaign_operations'] ?? null,
            ],
            'client' => [
                'display_name' => 'Client',
                'description' => 'Limited external-facing portal posture with no administrative modules by default.',
                'is_system' => true,
                'badge_color' => $palette['client'] ?? null,
            ],
        ];

        foreach ($profiles as $name => $meta) {
            Role::query()->where('name', $name)->update($meta);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $seedAdminEmails = ['test@example.com', 'admin@effective-media.test'];
        $seedAdmins = User::query()
            ->whereIn('email', $seedAdminEmails)
            ->get();

        foreach ($seedAdmins as $seedAdmin) {
            $seedAdmin->syncRoles(['super_admin']);
        }
    }
}
