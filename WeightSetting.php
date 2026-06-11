<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeightSetting extends Model
{
    protected $fillable = [
        'w_potential', 'w_competition', 'w_audience', 'w_internal',
        'go_threshold', 'review_threshold',
    ];

    public static function current(): self
    {
        return static::firstOrCreate([], []);
    }

    public function totalWeight(): int
    {
        return $this->w_potential + $this->w_competition + $this->w_audience + $this->w_internal;
    }
}
