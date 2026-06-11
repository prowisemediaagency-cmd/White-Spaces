<?php

namespace App\Http\Controllers;

use App\Models\Opportunity;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index(Request $request)
    {
        $query = Opportunity::with(['sector', 'anchorEvent'])->orderByDesc('score');

        if ($request->filled('country')) {
            $query->where('country', strtoupper($request->string('country')));
        }
        if ($request->filled('verdict')) {
            $query->where('verdict', $request->string('verdict'));
        }
        if ($request->filled('type')) {
            $query->where('opportunity_type', $request->string('type'));
        }

        return view('opportunities.index', [
            'opportunities' => $query->get(),
            'filters' => $request->only(['country', 'verdict', 'type']),
        ]);
    }
}
