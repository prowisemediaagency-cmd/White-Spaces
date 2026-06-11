@extends('layouts.app')

@section('title', 'Editar evento')

@section('content')
    <div class="page-head">
        <div>
            <p class="eyebrow">Eventos</p>
            <h1 class="title">Editar evento</h1>
            <p class="lead">{{ $event->name }}</p>
        </div>
    </div>

    <form method="POST" action="{{ route('events.update', $event) }}">
        @csrf
        @method('PUT')
        @include('events._form')
        <div class="form-actions">
            <button type="submit" class="btn btn--primary">Salvar alterações</button>
            <a href="{{ route('events.index') }}" class="btn">Cancelar</a>
        </div>
    </form>
@endsection
