<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Sector;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('sector')->orderBy('start_date');

        if ($request->filled('q')) {
            $query->where('name', 'like', '%'.$request->string('q').'%');
        }
        foreach (['country', 'year', 'division', 'event_type', 'status'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->string($field));
            }
        }
        if ($request->filled('sector_id')) {
            $query->where('sector_id', $request->integer('sector_id'));
        }

        return view('events.index', [
            'events' => $query->paginate(25)->withQueryString(),
            'sectors' => Sector::orderBy('name')->get(),
            'countries' => Event::select('country')->distinct()->orderBy('country')->pluck('country'),
            'years' => Event::select('year')->distinct()->orderBy('year')->pluck('year'),
        ]);
    }

    public function create()
    {
        return view('events.create', ['sectors' => Sector::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        Event::create($this->validated($request));

        return redirect()->route('events.index')->with('ok', 'Evento criado.');
    }

    public function edit(Event $event)
    {
        return view('events.edit', ['event' => $event, 'sectors' => Sector::orderBy('name')->get()]);
    }

    public function update(Request $request, Event $event)
    {
        $event->update($this->validated($request));

        return redirect()->route('events.index')->with('ok', 'Evento atualizado.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('ok', 'Evento removido.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'year' => ['required', 'integer', 'between:2020,2040'],
            'division' => ['required', 'in:'.implode(',', Event::DIVISIONS)],
            'event_type' => ['required', 'in:'.implode(',', Event::TYPES)],
            'status' => ['required', 'in:'.implode(',', Event::STATUSES)],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'country' => ['required', 'string', 'size:2'],
            'region' => ['nullable', 'string', 'max:40'],
            'city' => ['nullable', 'string', 'max:120'],
            'facility' => ['nullable', 'string', 'max:255'],
            'sector_id' => ['nullable', 'exists:sectors,id'],
        ]);
        $data['country'] = strtoupper($data['country']);

        return $data;
    }
}
