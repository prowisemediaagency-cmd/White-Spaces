<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    public const DIVISIONS = ['Informa Markets', 'Informa Connect', 'Informa Festivals', 'Informa Tech', 'Taylor & Francis'];
    public const TYPES = ['Trade Show', 'Conference', 'Consumer Show', 'Confex', 'Festival', 'Awards', 'Other'];
    public const STATUSES = ['Scheduled', 'Active', 'Traded', 'Cancelled', 'Pipeline'];

    protected $fillable = [
        'name', 'year', 'division', 'event_type', 'status',
        'start_date', 'end_date', 'country', 'region', 'facility', 'city', 'sector_id',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'year' => 'integer',
        ];
    }

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function scopeCountry(Builder $query, string $country): Builder
    {
        return $query->where('country', strtoupper($country));
    }
}
