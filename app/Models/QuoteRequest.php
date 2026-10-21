<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuoteRequest extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'full_name',
        'company_name',
        'phone',
        'email',
        'location',
        'county',
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
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
