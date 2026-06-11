<?php

namespace App\Http\Controllers;

use App\Models\Sector;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SectorController extends Controller
{
    public function index()
    {
        $sectors = Sector::withCount([
            'events as br_count' => fn ($q) => $q->where('country', 'BR'),
            'events as mx_count' => fn ($q) => $q->where('country', 'MX'),
            'events as global_count' => fn ($q) => $q->whereNotIn('country', ['BR', 'MX']),
        ])->orderBy('name')->get();

        return view('sectors.index', compact('sectors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => ['required', 'string', 'max:120', 'unique:sectors,name']]);

        Sector::create(['name' => $data['name'], 'slug' => Str::slug($data['name'])]);

        return back()->with('ok', 'Setor criado.');
    }

    public function destroy(Sector $sector)
    {
        $sector->delete();

        return back()->with('ok', 'Setor removido. Eventos vinculados ficaram sem setor.');
    }
}
