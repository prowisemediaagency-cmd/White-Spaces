@extends('layouts.app')

@section('title', 'Setores')

@section('content')
    <div class="page-head">
        <div>
            <p class="eyebrow">Taxonomia da análise</p>
            <h1 class="title">Setores</h1>
            <p class="lead">A análise compara cobertura por setor entre 🇧🇷, 🇲🇽 e o resto da casa. Eventos sem setor ficam fora da matriz — classifique-os na tela de Eventos.</p>
        </div>
    </div>

    <form method="POST" action="{{ route('sectors.store') }}" class="filters">
        @csrf
        <div class="filters__q">
            <label class="field-label" for="new-sector">Novo setor</label>
            <input type="text" id="new-sector" name="name" placeholder="Ex.: Petróleo & Gás" required>
            @error('name')<div class="field-error">{{ $message }}</div>@enderror
        </div>
        <div>
            <button type="submit" class="btn btn--primary">Adicionar setor</button>
        </div>
    </form>

    <div class="table-wrap">
        <table class="brutal">
            <thead>
                <tr>
                    <th>Setor</th>
                    <th>🇧🇷 Brasil</th>
                    <th>🇲🇽 México</th>
                    <th>Demais geografias</th>
                    <th>Leitura rápida</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sectors as $sector)
                    <tr>
                        <td><strong>{{ $sector->name }}</strong></td>
                        <td>{{ $sector->br_count }}</td>
                        <td>{{ $sector->mx_count }}</td>
                        <td>{{ $sector->global_count }}</td>
                        <td>
                            @if ($sector->br_count + $sector->mx_count + $sector->global_count === 0)
                                <span class="badge badge--muted">Sem eventos vinculados</span>
                            @elseif ($sector->mx_count === 0 && $sector->br_count === 0)
                                <span class="badge" style="background: var(--review);">White space BR + MX</span>
                            @elseif ($sector->mx_count === 0)
                                <span class="badge" style="background: var(--review);">White space MX</span>
                            @elseif ($sector->br_count === 0)
                                <span class="badge" style="background: var(--review);">White space BR</span>
                            @else
                                <span class="badge" style="background: var(--go); color: #fff;">Coberto em BR e MX</span>
                            @endif
                        </td>
                        <td class="td-actions">
                            <form method="POST" action="{{ route('sectors.destroy', $sector) }}" data-confirm="Remover o setor “{{ $sector->name }}”? Eventos vinculados ficarão sem setor.">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn--sm btn--danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
