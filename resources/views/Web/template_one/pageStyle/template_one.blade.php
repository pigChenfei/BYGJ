@if ($paginator->hasPages())
<ul class="pagination float-right">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <li class="disabled prevbtn"><a style="cursor: not-allowed">上一页</a></li>
    @else
    <li class="prevbtn"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">上一页</a></li>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $key=>$element)
    {{-- "Three Dots" Separator --}}
    @if (is_string($element))
    <li class="disabled"><a style="cursor: not-allowed">{{ $element }}</a></li>
    @endif
    {{-- Array Of Links --}}
    @if (is_array($element))
    @foreach ($element as $page => $url)
    @if((!in_array($key,[0,4])) && (($paginator->currentPage() == 3 && $page == 2) || ($paginator->currentPage() == ($paginator->lastPage()-2) && $page == ($paginator->lastPage()-1)))) 
    @else
    @if ($page == $paginator->currentPage())
    <li class="active"><a style="cursor: not-allowed">{{ $page }}</a></li>
    @else
    <li><a href="{{ $url }}">{{ $page }}</a></li>
    @endif
    @endif
    @endforeach
    @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <li class="nextbtn"><a href="{{ $paginator->nextPageUrl() }}" rel="next">下一页</a></li>
    @else
    <li class="disabled nextbtn"><a style="cursor: not-allowed">下一页</a></li>
    @endif
</ul>
@endif