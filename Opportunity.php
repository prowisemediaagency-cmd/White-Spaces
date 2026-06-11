<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Opportunity extends Model
{
    public const TYPE_LABELS = [
        'new_event' => 'Novo evento',
        'geo_clone' => 'Geo-clone de marca da casa',
        'brand_import' => 'Importar marca global',
        'co_location' => 'Co-location',
        'portfolio_adjustment' => 'Ajuste de portfolio',
    ];

    protected $fillable = [
        'sector_id', 'country', 'opportunity_type', 'verdict',
        'score', 'rationale', 'score_breakdown', 'anchor_event_id',
    ];

    public function sector(): BelongsTo
    {
        return $this->belongsTo(Sector::class);
    }

    public function anchorEvent(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'anchor_event_id');
    }

    public function typeLabel(): string
    {
        return self::TYPE_LABELS[$this->opportunity_type] ?? $this->opportunity_type;
    }
}
