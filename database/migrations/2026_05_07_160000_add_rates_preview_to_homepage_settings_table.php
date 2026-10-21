<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return array{kicker: string, title: string, tiers: list<array{tier_label: string, price: string, description: string, cta_label: string, cta_href: string, highlighted: bool}>}
     */
    private function ratesPreviewSeed(): array
    {
        $quoteStarter = route('quote', ['budget_range' => 'KES 100K - 300K'], absolute: false);
        $quoteGrowth = route('quote', ['budget_range' => 'KES 300K - 750K'], absolute: false);
        $contact = route('contact-us', [], absolute: false);

        return [
            'kicker' => 'Rates Preview',
            'title' => 'Estimate Campaign Costs Fast',
            'tiers' => [
                [
                    'tier_label' => 'Starter',
                    'price' => 'KES 120K',
                    'description' => 'Street light placements for local market reach.',
                    'cta_label' => 'Get Estimate',
                    'cta_href' => $quoteStarter,
                    'highlighted' => false,
                ],
                [
                    'tier_label' => 'Growth',
                    'price' => 'KES 450K',
                    'description' => 'Multi-town deployment with CBD + highway impact.',
                    'cta_label' => 'Plan Package',
                    'cta_href' => $quoteGrowth,
                    'highlighted' => true,
                ],
                [
                    'tier_label' => 'Dominance',
                    'price' => 'KES 900K+',
                    'description' => 'National coverage and premium visibility corridors.',
                    'cta_label' => 'Talk to Sales',
                    'cta_href' => $contact,
                    'highlighted' => false,
                ],
            ],
        ];
    }

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('homepage_settings', function (Blueprint $table): void {
            $table->json('rates_preview')->nullable()->after('secondary_cta_link');
        });

        $seed = json_encode($this->ratesPreviewSeed(), JSON_THROW_ON_ERROR);
        DB::table('homepage_settings')->whereNull('rates_preview')->update(['rates_preview' => $seed]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homepage_settings', function (Blueprint $table): void {
            $table->dropColumn('rates_preview');
        });
    }
};
