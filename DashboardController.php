<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Opportunity;
use App\Models\Sector;

class DashboardController extends Controller
{
    public function index()
    {
        $sectors = Sector::withCount([
            'events as br_count' => fn ($q) => $q->where('country', 'BR'),
            'events as mx_count' => fn ($q) => $q->where('country', 'MX'),
            'events as global_count' => fn ($q) => $q->whereNotIn('country', ['BR', 'MX']),
        ])->orderBy('name')->get();

        $covered = $sectors->filter(fn ($s) => $s->br_count + $s->mx_count + $s->global_count > 0);

        return view('dashboard', [
            'totalEvents' => Event::count(),
            'brEvents' => Event::country('BR')->count(),
            'mxEvents' => Event::country('MX')->count(),
            'sectorCount' => $covered->count(),
            'whiteSpacesBr' => $covered->filter(fn ($s) => $s->br_count === 0 && ($s->mx_count + $s->global_count) > 0),
            'whiteSpacesMx' => $covered->filter(fn ($s) => $s->mx_count === 0 && ($s->br_count + $s->global_count) > 0),
            'coverage' => $covered,
            'topOpportunities' => Opportunity::with(['sector', 'anchorEvent'])
                ->orderByDesc('score')->limit(6)->get(),
            'goCount' => Opportunity::where('verdict', 'GO')->count(),
        ]);
    }
}
