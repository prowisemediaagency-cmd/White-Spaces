<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Informa WhiteSpace Radar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;600;700&family=Archivo+Black&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="topbar">
        <div class="container topbar__inner">
            <a href="{{ route('dashboard') }}" class="wordmark">
                <span class="wordmark__informa">informa</span>
                <span class="wordmark__app">WhiteSpace Radar · BR×MX</span>
            </a>
            <nav class="nav" aria-label="Navegação principal">
                <a href="{{ route('dashboard') }}" @class(['is-active' => request()->routeIs('dashboard')])>Dashboard</a>
                <a href="{{ route('events.index') }}" @class(['is-active' => request()->routeIs('events.*')])>Eventos</a>
                <a href="{{ route('sectors.index') }}" @class(['is-active' => request()->routeIs('sectors.*')])>Setores</a>
                <a href="{{ route('matrix.index') }}" @class(['is-active' => request()->routeIs('matrix.*')])>Matriz decisória</a>
                <a href="{{ route('opportunities.index') }}" @class(['is-active' => request()->routeIs('opportunities.*')])>Recomendações</a>
            </nav>
        </div>
    </header>

    @yield('ticker')

    <main class="page">
        <div class="container">
            @if (session('ok'))
                <div class="flash" role="status">{{ session('ok') }}</div>
            @endif

            @if ($errors->any())
                <div class="errors-box" role="alert">
                    <strong>Corrija antes de salvar:</strong>
                    @foreach ($errors->all() as $error)
                        <div>— {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <footer class="footer">
        <div class="container">
            Informa WhiteSpace Radar — ferramenta interna de análise de portfólio. Scores refletem julgamento do time, não dados auditados de mercado.
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
