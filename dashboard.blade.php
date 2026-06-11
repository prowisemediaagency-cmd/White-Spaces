@extends('layouts.app')

@section('title', 'Dashboard')

@section('ticker')
    <div class="ticker" aria-hidden="true">
        <div class="ticker__track">
            @for ($i = 0; $i < 2; $i++)
                <span>{{ $totalEvents }} eventos no radar</span>
                <span>🇧🇷 Brasil: {{ $brEvents }}</span>
                <span>🇲🇽 México: {{ $mxEvents }}</span>
                <span>{{ $sectorCount }} setores mapeados</span>
                <span>{{ $whiteSpacesMx->count() }} white spaces MX</span>
                <span>{{ $whiteSpacesBr->count() }} white spaces BR</span>
                <span>{{ $goCount }} recomendações GO</span>
            @endfor
        </div>
    </div>
@endsection

@section('content')
    <div class="page-head">
        <div>
            <p class="eyebrow">Portfólio global → foco Brasil × México</p>
            <h1 class="title">Onde o portfólio<br>ainda não chegou</h1>
            <p class="lead">Cobertura por setor, lacunas por geografia e recomendações carimbadas. Os números abaixo vêm da base de eventos — edite-a em <a href="{{ route('events.index') }}">Eventos</a> e os white spaces se recalculam sozinhos.</p>
        </div>
        <form method="POST" action="{{ route('analysis.run') }}">
            @csrf
            <button type="submit" class="btn btn--primary">Reexecutar análise</button>
        </form>
    </div>

    @if ($mxEvents <= 1)
        <div class="note" style="margin-bottom: 22px;">
            <strong>Alerta de dado, não de mercado:</strong> o México tem {{ $mxEvents }} evento(s) na base. Com essa amostra, quase todo setor vira "white space MX" por ausência de cadastro, não por ausência de cobertura real. Carregue o portfólio mexicano completo antes de levar os vereditos para um comitê.
        </div>
    @endif

    <div class="grid grid--stats">
        <div class="stat stat--ink">
            <span class="stat__value">{{ $totalEvents }}</span>
            <span class="stat__label">Eventos no portfólio</span>
        </div>
        <div class="stat">
            <span class="stat__value">{{ $brEvents }}</span>
            <span class="stat__label">🇧🇷 Eventos no Brasil</span>
        </div>
        <div class="stat stat--pink">
            <span class="stat__value">{{ $mxEvents }}</span>
            <span class="stat__label">🇲🇽 Eventos no México</span>
        </div>
        <div class="stat">
            <span class="stat__value">{{ $sectorCount }}</span>
            <span class="stat__label">Setores com cobertura</span>
        </div>
        <div class="stat">
            <span class="stat__value">{{ $goCount }}</span>
            <span class="stat__label">Recomendações GO</span>
        </div>
    </div>

    <h2 class="section-title">White spaces por geografia</h2>
    <div class="grid grid--2">
        <div class="card">
            <p class="eyebrow">🇲🇽 México — setores sem evento local</p>
            @forelse ($whiteSpacesMx as $sector)
                <span class="badge badge--muted" style="margin: 0 6px 8px 0;">{{ $sector->name }} <strong>({{ $sector->br_count + $sector->global_count }} na casa)</strong></span>
            @empty
                <p>Nenhum white space: todos os setores mapeados têm evento no México.</p>
            @endforelse
        </div>
        <div class="card">
            <p class="eyebrow">🇧🇷 Brasil — setores sem evento local</p>
            @forelse ($whiteSpacesBr as $sector)
                <span class="badge badge--muted" style="margin: 0 6px 8px 0;">{{ $sector->name }} <strong>({{ $sector->mx_count + $sector->global_count }} na casa)</strong></span>
            @empty
                <p>Nenhum white space: todos os setores mapeados têm evento no Brasil.</p>
            @endforelse
        </div>
    </div>

    <h2 class="section-title">Cobertura do portfólio por setor</h2>
    <div class="card">
        <div class="coverage">
            @foreach ($coverage as $sector)
                <div class="coverage__row">
                    <span class="coverage__label">{{ $sector->name }}</span>
                    <span class="coverage__bar">
                        @for ($i = 0; $i < $sector->br_count; $i++)<span class="coverage__cell coverage__cell--br" title="Brasil"></span>@endfor
                        @for ($i = 0; $i < $sector->mx_count; $i++)<span class="coverage__cell coverage__cell--mx" title="México"></span>@endfor
                        @for ($i = 0; $i < min($sector->global_count, 18); $i++)<span class="coverage__cell coverage__cell--global" title="Global"></span>@endfor
                        @if ($sector->global_count > 18)<span class="badge badge--muted">+{{ $sector->global_count - 18 }}</span>@endif
                        @if ($sector->br_count === 0)<span class="coverage__zero">BR 0</span>@endif
                        @if ($sector->mx_count === 0)<span class="coverage__zero">MX 0</span>@endif
                    </span>
                </div>
            @endforeach
        </div>
        <div class="legend">
            <span><span class="coverage__cell coverage__cell--br"></span> Brasil</span>
            <span><span class="coverage__cell coverage__cell--mx"></span> México</span>
            <span><span class="coverage__cell coverage__cell--global"></span> Demais geografias</span>
        </div>
    </div>

    <h2 class="section-title">Top recomendações por score</h2>
    @if ($topOpportunities->isEmpty())
        <div class="card">
            <p>Nenhuma recomendação gerada ainda. Avalie os setores na <a href="{{ route('matrix.index') }}">Matriz decisória</a> e clique em <strong>Reexecutar análise</strong>.</p>
        </div>
    @else
        <div class="grid grid--2">
            @foreach ($topOpportunities as $opp)
                <div class="opp">
                    <div class="opp__head">
                        <div>
                            <div class="opp__sector">{{ $opp->sector->name }}</div>
                            <div class="opp__meta">
                                <span class="badge badge--country">{{ $opp->country === 'BR' ? '🇧🇷 Brasil' : '🇲🇽 México' }}</span>
                                <span class="badge">{{ $opp->typeLabel() }}</span>
                            </div>
                        </div>
                        <span class="stamp stamp--sm stamp--{{ strtolower(str_replace('-', '', $opp->verdict)) }}">{{ $opp->verdict }}</span>
                    </div>
                    <div class="opp__score">{{ number_format($opp->score, 1) }}<small>score / 100</small></div>
                    <p class="opp__rationale">{{ Str::limit($opp->rationale, 220) }}</p>
                </div>
            @endforeach
        </div>
        <p style="margin-top: 18px;"><a class="btn" href="{{ route('opportunities.index') }}">Ver todas as recomendações</a></p>
    @endif
@endsection
