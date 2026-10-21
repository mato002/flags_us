<?php

use App\Services\Cms\WebsitePageSectionsService;

/**
 * Default public website page sections (CMS merge target).
 * Editors override via Admin → Website pages → JSON sections (deep merge).
 *
 * Each slug owns a distinct narrative arc — avoid copy-pasting phrases across pages.
 *
 * @see WebsitePageSectionsService
 */
return [
    'slugs' => [
        'home',
        'who-we-are',
        'what-we-do',
        'portfolio',
        'coverage',
        'smart-campaign-planner',
        'quote',
        'faqs',
        'contact-us',
    ],

    'pages' => [
        'who-we-are' => [
            '_meta' => [
                'default_title' => 'Who We Are | Effective Media',
                'default_meta' => 'Ownership of crews, assets, and maintenance rhythms across Kenya — built for brands that sign national outdoor contracts and expect field truth.',
            ],
            'hero' => [
                'layout' => 'editorial_split',
                'kicker' => 'Operator-led network',
                'title' => 'Installations, maintenance, and campaign execution under one roof',
                'description' => 'We are not a listing marketplace. Teams answer for poles, faces, print cycles, night installs, and corrective upkeep — the unglamorous backbone that makes visibility durable.',
                'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=2000&q=80',
                'primary_cta_label' => 'Review inventory footprint',
                'primary_cta_url' => '/coverage',
                'secondary_cta_label' => 'Study published work',
                'secondary_cta_url' => '/portfolio',
            ],
            'company_story' => [
                'label' => 'Trajectory',
                'title' => 'Credibility earned through repeat corridor delivery',
                'intro' => '',
                'milestones_note' => 'Structured timeline entries also hydrate from your site profile when intro is left blank.',
            ],
            'operational_capability' => [
                'label' => 'Operating model',
                'title' => 'Nationwide scale only works when every link is accountable',
                'body' => 'Permitting posture, production calendars, signal-side placement windows, and reactive maintenance sit in one operational spine — so finance, procurement, and agencies hear one version of the truth.',
                'bullets' => [
                    'Town and route planning anchored to a live internal register — not third-party estimates',
                    'Printing, flighting, and QA milestones tied to dated install evidence',
                    'Cleaning, repair, and lamp-out response owned by in-house field squads',
                    'Handover packs formatted for LPO alignment without rewrites',
                ],
            ],
            'mission' => [
                'label' => 'North star',
                'title' => 'What we optimize for',
                'mission_title' => 'Mission',
                'vision_title' => 'Vision',
            ],
            'deployment_workflow' => [
                'label' => 'From signature to illuminated faces',
                'title' => 'Six gates every national brief passes',
                'steps' => [
                    ['title' => 'Scope lock', 'detail' => 'Objectives become route types, town stack rank, and countable units with alternates.'],
                    ['title' => 'Inventory hold', 'detail' => 'Faces are reserved against your flight window with backup corridors named.'],
                    ['title' => 'Print & compliance', 'detail' => 'Spec proofing, council-facing notes, and fabrication sign-off before reels move.'],
                    ['title' => 'Night install', 'detail' => 'Field teams execute with photo proof and snag lists closed before handover.'],
                    ['title' => 'In-market care', 'detail' => 'Rotation cleaning, damage swaps, and illumination checks on a published cadence.'],
                    ['title' => 'Close-out', 'detail' => 'Extension options, dismantle timing, or refreshed creative planning for the next window.'],
                ],
            ],
            'trust_metrics' => [
                'label' => 'Register snapshot',
                'title' => 'Why buyers ask for hard numbers before mandate fees',
                'subtitle' => 'Single-operator accountability from artwork release through maintenance rotations.',
                'note' => 'Blends register exports with marketing claims — adjust in admin if you need tighter legal language.',
            ],
            'maintenance_capability' => [
                'label' => 'Uptime discipline',
                'title' => 'Visibility decays quietly — we schedule against commute memory',
                'body' => 'Angles drift, vinyl dulls, storms nick edges. We maintain against those failures before brand teams hear about them from retail or franchise partners.',
                'image' => 'profile-gallery/profile-page-6.jpg',
                'points' => [
                    'Route-level cleaning windows with optional photo packs for HQ teams',
                    'Damage tickets with unit-type SLAs and swap tracking',
                    'Audit-friendly evidence when procurement requests proof of flight quality',
                ],
            ],
            'why_brands' => [
                'label' => 'Why procurement teams return',
                'title' => 'Operational receipts over slide decks',
                'items' => [],
            ],
            'proof_strip' => [
                'label' => 'Evidence room',
                'title' => 'Judge us on finished roadsides',
                'cta_label' => 'Browse executions',
                'cta_url' => '/portfolio',
                'secondary_label' => 'Map the footprint',
                'secondary_url' => '/coverage',
            ],
            'closing_cta' => [
                'title' => 'Brief the operations desk',
                'description' => 'Share towns, timing, and risk tolerance — we answer with reserved units and named alternates.',
                'primary_label' => 'Open structured brief',
                'primary_url' => '/quote',
                'secondary_label' => 'Speak with sales',
                'secondary_url' => '/contact-us',
            ],
        ],

        'what-we-do' => [
            '_meta' => [
                'default_title' => 'What We Do | Effective Media',
                'default_meta' => 'Format-specific outdoor products — each with its own audience physics, creative rules, and buying motion.',
            ],
            'hero' => [
                'layout' => 'band',
                'kicker' => 'Format intelligence',
                'title' => 'Different surfaces, different attention economics',
                'description' => 'Street furniture, large format, pavement, workplace, and live formats all appear on roadsides — but they answer different memory and decision problems. Pick the job, not the asset name.',
                'primary_cta_label' => 'Jump to formats',
                'primary_cta_anchor' => '#product-explorer',
                'secondary_cta_label' => 'Simulate mixes first',
                'secondary_cta_url' => '/smart-campaign-planner',
                'image' => 'https://images.unsplash.com/photo-1519501025264-65ba15a82390?auto=format&fit=crop&w=2200&q=80',
                'floating_note' => 'Each format below owns its own story, imagery, and call-to-action — pulled from Services CMS and format presets.',
            ],
            'product_explorer' => [
                'label' => 'Format runway',
                'title' => 'Six mobility problems, six differentiated answers',
                'description' => 'Rows alternate layout,s copy, bullets, and buttons per service record — avoiding cookie-cutter “capability cards.”',
            ],
            'street_spotlight' => [
                'label' => 'Signature lattice',
                'title' => 'Street-light rhythm for habitual commuters',
                'body' => '',
                'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=2000&q=82',
                'footnote' => 'Volumes reflect your consolidated register exports when synced — otherwise illustrative.',
            ],
            'format_links' => [
                'coverage' => 'Where we own faces',
                'contact' => 'Ask a format lead',
            ],
            'campaign_structures' => [
                'label' => 'Brief archetypes',
                'title' => 'Three illustrative brief shapes (not exhaustive)',
                'description' => 'Shows how buys change when repetition, stature, or proximity leads — swap numbers in your live planner output.',
                'cases' => [
                    ['client' => 'Daily-drive FMCG', 'location' => 'Nakuru CBD cluster', 'format' => 'Street light lattice', 'units' => '28–36 faces', 'duration' => '90 days', 'outcome' => 'Sustain route memory across school and work pulses'],
                    ['client' => 'National prestige launch', 'location' => 'Highway apex + beltway', 'format' => 'Large format + pole assist', 'units' => '12 + 40 faces', 'duration' => '45 days', 'outcome' => 'Category authority plus suburban reinforcement'],
                    ['client' => 'Retail uplift window', 'location' => 'Pedestrian high street', 'format' => 'Pavement + local poles', 'units' => '18 units', 'duration' => '6 weeks', 'outcome' => 'Offer proximity at decision distance'],
                ],
            ],
            'planner_teaser' => [
                'label' => 'When the format isn’t obvious',
                'title' => 'Stress-test mixes before reserving units',
                'description' => 'The assistant weighs place, audiences, budgets, and duration — emitting a directional mix without locking rates.',
                'cta_label' => 'Open assistant',
                'cta_url' => '/smart-campaign-planner',
                'secondary_label' => 'Skip to procurement form',
                'secondary_url' => '/quote',
            ],
            'documents' => [
                'label' => 'Collateral locker',
                'title' => 'PDFs anchored to geography and councils',
                'subtitle' => 'Upload variants for legal or franchise reviewers — downloads mirror admin storage.',
            ],
            'closing' => [
                'title' => 'Reserve towns with naming precision',
                'primary_label' => 'Request holdings',
                'primary_url' => '/quote',
                'secondary_label' => 'Inspect footprint math',
                'secondary_url' => '/coverage',
            ],
        ],

        'portfolio' => [
            '_meta' => [
                'default_title' => 'Portfolio | Effective Media',
                'default_meta' => 'Executed outdoor campaigns across Kenya — creative outcomes, counties, sectors, and field notes.',
            ],
            'hero' => [
                'kicker' => 'Field anthology',
                'title' => 'Photographic proof from real flights',
                'description' => 'This gallery is verdict space: creatives, substrates, municipalities, and partners named. Quantitative proofs and heat layers live exclusively on Coverage.',
                'image' => 'https://images.unsplash.com/photo-1480714378408-67cf0d13bc1f?auto=format&fit=crop&w=2000&q=82',
            ],
            'gallery' => [
                'label' => 'Published executions',
                'title' => 'Filter by media archetype',
                'description' => 'CMS items drive tiles — add narrative when legal clears client visibility.',
                'item_placeholder_story' => 'Story pending editor notes — enrich this execution in Portfolio CMS.',
            ],
            'counties' => [
                'label' => 'County spread',
                'title' => 'Where these flights cleared planning',
                'description' => 'Chips summarise register presence — drill into totals from Coverage, not duplicated tables here.',
            ],
            'industries' => [
                'label' => 'Industry heat',
                'title' => 'Verticals recurring on our metal',
            ],
            'before_after' => [
                'label' => 'Condition narrative',
                'title' => 'Surface state → finished visibility',
                'pairs' => [
                    ['before_caption' => 'Pre-install audit', 'after_caption' => 'Illuminated sequence', 'before_image' => null, 'after_image' => null],
                    ['before_caption' => 'Weathered substrate', 'after_caption' => 'High-contrast relight', 'before_image' => null, 'after_image' => null],
                ],
            ],
            'deployments' => [
                'label' => 'Publishing cadence',
                'title' => 'Typical fortnight choreography on large buys',
                'items' => [
                    ['phase' => 'Kickoff sprint', 'detail' => 'Creative lock plus council packet drops'],
                    ['phase' => 'Fabrication + staging', 'detail' => 'Print QA, reels crated for night lifts'],
                    ['phase' => 'Live stewardship', 'detail' => 'Photo sign-off packs + maintenance windows'],
                ],
            ],
            'documents' => [
                'label' => 'Collateral shelf',
                'title' => 'PDFs adjudicators request mid-tender',
                'subtitle' => 'Legal often wants paper alongside photography — mirror versions here.',
                'aside_title' => 'After you shortlist creatives',
                'aside_body' => 'Validate residual holdings and interchange loads before treasury signs — Coverage holds the authoritative counts; Quote captures commercial intent.',
                'aside_footer' => 'Pricing scenarios stay on Quote to protect margin conversations beside creative applause.',
            ],
            'cta' => [
                'title' => 'Need counts beside these stories?',
                'primary_label' => 'Open inventory explorer',
                'primary_url' => '/coverage',
                'secondary_label' => 'Transmit a formal brief',
                'secondary_url' => '/quote',
            ],
        ],

        'coverage' => [
            '_meta' => [
                'default_title' => 'Coverage & Inventory | Effective Media',
                'default_meta' => 'Kenya holdings snapshot: GIS explorer, tally registers, arterial context, mobility envelopes.',
            ],
            'hero' => [
                'kicker' => 'Infrastructure atlas',
                'title' => 'Pin towns, reconcile poles, expose corridors',
                'description' => 'Built for forecasting teams: reconcile heat, registers, arterial commentary, and movement framing before anyone talks discounts. Imagery proofs remain on Portfolio.',
                'image' => 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=2000&q=80',
                'primary_cta_label' => 'Open live explorer',
                'primary_cta_anchor' => '#coverage-map',
                'secondary_cta_label' => 'Raise a holdings check',
                'secondary_cta_url' => '/quote',
            ],
            'county_snapshot' => [
                'label' => 'County fabric',
                'title' => 'Anchors available for brief alignment',
                'description' => 'Figures summarise ledger exports — escalate discrepancies internally before client-facing decks.',
            ],
            'inventory_stats' => [
                'label' => 'Registers',
                'title' => 'Rolling totals from consolidated exports',
            ],
            'map' => [
                'label' => 'Explorer',
                'title' => 'Tap pins → hydrate insight rail',
                'description' => 'Markers summarise pole rollups — pair with Procurement form when bids harden.',
            ],
            'highways' => [
                'label' => 'Arterial lens',
                'title' => 'Where long-haul and radial traffic dominates sightlines',
                'items' => [
                    ['corridor' => 'A104 commuter spines', 'note' => 'Signal dwell stacks with approach reads for large faces'],
                    ['corridor' => 'County connectors', 'note' => 'Blend of freeway panels and rhythmic pole arcs'],
                    ['corridor' => 'Urban cores', 'note' => 'Pedestrian cadence intersecting slow-roll traffic'],
                ],
            ],
            'transport_visibility' => [
                'label' => 'Mobility envelopes',
                'title' => 'Speed and sight triangles change readable copy',
                'body' => 'Matatu belts, trucking corridors, and walkable precincts ingest creative differently — we annotate each holding with dominant motion context.',
                'cta_label' => 'Stress-test holdings in assistant',
                'cta_url' => '/smart-campaign-planner',
            ],
            'closing' => [
                'card_kicker' => 'Next move',
                'title' => 'Bring a disputed town list',
                'description' => 'We reconcile deltas against this dataset before issuing commercial numbers.',
                'primary_label' => 'Lock a reconciliation brief',
                'primary_url' => '/quote',
            ],
        ],

        'smart-campaign-planner' => [
            '_meta' => [
                'default_title' => 'Planning Assistant | Effective Media',
                'default_meta' => 'Scenario workspace for Kenyan OOH mixes — geography, audiences, durations, envelopes — without implying final rates.',
            ],
            'hero' => [
                'kicker' => 'Scenario lab',
                'title' => 'Prototype mixes before anyone signs an LOI',
                'description' => 'Strategists usually rank place, pressure, and envelope in that order. This surface lets you do the same with live markers for sanity checks — not rate authority.',
                // Urban OOH — road + billboards (fits media / coverage; not finance dashboards) — Unsplash
                'image' => 'https://images.unsplash.com/photo-1576608583800-2dea5261c7ee?auto=format&fit=crop&w=2400&q=85',
                'pill' => 'Outputs sync with form defaults on Quote when you export',
            ],
            'workflow_lane' => [
                'label' => 'Lane logic',
                'steps' => [
                    ['title' => 'Anchor geography', 'detail' => 'County → town selection inherits marker context'],
                    ['title' => 'Load pressures', 'detail' => 'Industry, objective, and audience shape language'],
                    ['title' => 'Bound spend & time', 'detail' => 'Bands suggest weighting — not quotations'],
                    ['title' => 'Export storyline', 'detail' => 'Copy chips lift into Quote or WhatsApp verbatim'],
                ],
            ],
            'assistant_panel_copy' => [
                'heading' => 'How this surface behaves',
                'body' => 'Recommendations reorganise headline chips, locality language, rationale blurbs, and focus labels whenever inputs shift — deliberately unlike static FAQs.',
            ],
            'workspace' => [
                'form_title' => 'Input variables',
                'map_explorer_label' => 'Marker rehearsal',
                'submit_label' => 'Run scenario',
                'section_location_id' => 'location',
            ],
            'recommendation_panel' => [
                'rail_label' => 'Scenario output',
                'headline_placeholder' => 'Pick geography to initialise',
                'summary_seed' => 'Choose county, objective, and envelope — commentary assembles contextually.',
                'focus_heading' => 'Suggested environments',
                'budget_heading' => 'Envelope posture',
                'export_quote_label' => 'Push storyline to Quote',
                'whatsapp_label' => 'Escalate on WhatsApp',
                'detail_corridors' => 'Environmental read',
                'detail_media' => 'Format emphasis',
                'detail_logic' => 'Why this weighting appears',
                'map_hint' => 'Tap markers to preload selects — narration follows the geography you prove on-map.',
            ],
        ],

        'quote' => [
            '_meta' => [
                'default_title' => 'Request Proposal | Effective Media',
                'default_meta' => 'Premium structured brief for outdoor campaigns — routed to planners with inventory awareness.',
            ],
            'hero' => [
                'kicker' => 'Formal intake',
                'title' => 'One submission, multiple internal stakeholders',
                'description' => 'Capture identity, locality, ambition, commercials, nuance — the sticky rail mirrors edits so desk reviews stay synchronized.',
                'primary_note' => 'Completeness accelerates allocator routing internally.',
                'accent' => 'warm',
                'image' => 'https://images.unsplash.com/photo-1578575437130-527eed3abb25?auto=format&fit=crop&w=2000&q=82',
            ],
            'trust' => [
                'items' => [
                    ['title' => 'Dual-desk acknowledgement', 'body' => 'Metro coordination pairs with corridor field leads on every inbound.', 'badge' => 'Ops'],
                    ['title' => 'Register-informed responses', 'body' => 'Replies cite holdings—not speculative availability.', 'badge' => 'Data'],
                    ['title' => 'Packaged evidence', 'body' => 'PDF profiles plus annex cues on request.', 'badge' => 'Docs'],
                ],
            ],
            'summary_copy' => [
                'heading' => 'Live intake mirror',
                'hint' => 'Changes propagate instantly as you descend the form.',
                'panel_title' => 'Brief synopsis',
                'labels' => [
                    'county' => 'County priority',
                    'location' => 'Town / axial focus',
                    'media' => 'Preferred formats',
                    'budget' => 'Spend envelope',
                    'duration' => 'Flight length',
                ],
            ],
            'form' => [
                'submit_label' => 'Transmit brief',
                'legalese' => 'Submitting confirms Effective Media may contact you about proposed holdings consistent with posted privacy posture.',
                'contact_link_label' => 'Prefer a conversation first?',
                'aside_help_title' => 'Need immediacy?',
                'aside_help_body' => 'Message sales with counties and timelines — identical desk triages conversational and form inbound.',
                'whatsapp_button' => 'Message sales desk',
            ],
        ],
        'home' => [
            '_meta' => [
                'default_title' => 'Effective Media | Outdoor Advertising Infrastructure',
                'default_meta' => 'Strategic outdoor advertising infrastructure across highways, CBDs, transport corridors and urban centers.',
            ],
            'hero' => [],
        ],
        'faqs' => [
            '_meta' => [
                'default_title' => 'FAQs | Effective Media',
                'default_meta' => 'Frequently asked questions about formats, pricing, artworks, deployment windows, and campaign operations.',
            ],
            'hero' => [],
        ],
        'contact-us' => [
            '_meta' => [
                'default_title' => 'Contact Us | Effective Media',
                'default_meta' => 'Get in touch with the Effective Media team for campaign briefs, support, and planning guidance.',
            ],
            'hero' => [],
        ],
    ],
];
