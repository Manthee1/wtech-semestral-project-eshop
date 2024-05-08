@props(['paginator'])

<div class="pagination">
    @php
        $currentPage = $paginator->currentPage();
        $lastPage = $paginator->lastPage();
        $startPage = max(1, $currentPage - 2);
        $endPage = min($startPage + 4, $lastPage);
        $requestParams = request()->except('page');
        $queryString = '&' . http_build_query($requestParams);
    @endphp
    @if ($startPage > 1)
        <a class="button-icon-clear" href="{{ $paginator->url($currentPage - 1) . $queryString }}">
            <ion-icon name="chevron-back"></ion-icon>
        </a>
    @endif
    <ol>
        @if ($startPage > 1)
            <li><a href="{{ $paginator->url(1) . $queryString }}">1</a></li>
            @if ($startPage > 2)
                <li><span>...</span></li>
            @endif
        @endif
        @for ($i = $startPage; $i <= $endPage; $i++)
            <li class="{{ $i == $currentPage ? 'active' : '' }}"><a href="{{ $paginator->url($i) . $queryString }}">{{ $i }}</a></li>
        @endfor
        @if ($endPage < $lastPage)
            @if ($endPage < $lastPage - 1)
                <li><span>...</span></li>
            @endif
            <li><a href="{{ $paginator->url($lastPage) . $queryString }}">{{ $lastPage }}</a></li>
        @endif
    </ol>
    </ol>
    </ol>
    @if ($endPage < $lastPage)
        <a class="button-icon-clear" href="{{ $paginator->url($currentPage + 1) . $queryString }}">
            <ion-icon name="chevron-forward"></ion-icon>
        </a>
    @endif
</div>
