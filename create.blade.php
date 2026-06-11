@extends('layouts.app')

@section('title', 'Novo evento')

@section('content')
    <div class="page-head">
        <div>
            <p class="eyebrow">Eventos</p>
            <h1 class="title">Novo evento</h1>
        </div>
    </div>

    <form method="POST" action="{{ route('events.store') }}">
        @csrf
        @include('events._form')
        <div class="form-actions">
            <button type="submit" class="btn btn--primary">Criar evento</button>
            <a href="{{ route('events.index') }}" class="btn">Cancelar</a>
        </div>
    </form>
@endsection
