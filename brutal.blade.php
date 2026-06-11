@if ($paginator->hasPages())
    <nav class="pagination" role="navigation" aria-label="Paginação">
        @if ($paginator->onFirstPage())
            <span class="is-disabled">‹ Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev">‹ Anterior</a>
        @endif

        @foreach ($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
            @if ($page == $paginator->currentPage())
                <span class="is-current" aria-current="page">{{ $page }}</span>
            @else
                <a href="{{ $url }}">{{ $page }}</a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next">Próxima ›</a>
        @else
            <span class="is-disabled">Próxima ›</span>
        @endif
    </nav>
@endif
