@php($event = $event ?? null)
<div class="card">
    <div class="form-grid">
        <div class="span-2">
            <label class="field-label" for="name">Nome do evento</label>
            <input type="text" id="name" name="name" value="{{ old('name', $event?->name) }}" required>
            @error('name')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="field-label" for="year">Ano-edição</label>
            <input type="number" id="year" name="year" min="2020" max="2040" value="{{ old('year', $event?->year ?? now()->year) }}" required>
            @error('year')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="field-label" for="country">País (ISO-2, ex.: BR, MX)</label>
            <input type="text" id="country" name="country" maxlength="2" style="text-transform: uppercase;" value="{{ old('country', $event?->country) }}" required>
            @error('country')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="field-label" for="region">Região do show</label>
            <input type="text" id="region" name="region" value="{{ old('region', $event?->region) }}" placeholder="Ex.: South America">
        </div>
        <div>
            <label class="field-label" for="division">Divisão</label>
            <select id="division" name="division" required>
                @foreach (\App\Models\Event::DIVISIONS as $d)
                    <option value="{{ $d }}" @selected(old('division', $event?->division) === $d)>{{ $d }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="field-label" for="event_type">Tipo</label>
            <select id="event_type" name="event_type" required>
                @foreach (\App\Models\Event::TYPES as $t)
                    <option value="{{ $t }}" @selected(old('event_type', $event?->event_type) === $t)>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="field-label" for="status">Status</label>
            <select id="status" name="status" required>
                @foreach (\App\Models\Event::STATUSES as $s)
                    <option value="{{ $s }}" @selected(old('status', $event?->status ?? 'Scheduled') === $s)>{{ $s }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="field-label" for="sector_id">Setor (alimenta a análise)</label>
            <select id="sector_id" name="sector_id">
                <option value="">— Sem setor —</option>
                @foreach ($sectors as $s)
                    <option value="{{ $s->id }}" @selected(old('sector_id', $event?->sector_id) == $s->id)>{{ $s->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="field-label" for="start_date">Início</label>
            <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $event?->start_date?->format('Y-m-d')) }}">
        </div>
        <div>
            <label class="field-label" for="end_date">Fim</label>
            <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $event?->end_date?->format('Y-m-d')) }}">
            @error('end_date')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="field-label" for="city">Cidade</label>
            <input type="text" id="city" name="city" value="{{ old('city', $event?->city) }}">
        </div>
        <div class="span-2">
            <label class="field-label" for="facility">Local / venue</label>
            <input type="text" id="facility" name="facility" value="{{ old('facility', $event?->facility) }}">
        </div>
    </div>
</div>
