@if ($paginator->hasPages())
<div class="d-flex justify-content-center align-items-center gap-1 my-2">

    {{-- Nút Trước --}}
    @if ($paginator->onFirstPage())
        <span class="btn btn-sm btn-outline-secondary disabled" style="min-width:80px">
            <i class="fas fa-chevron-left me-1" style="font-size:0.7rem"></i> Trước
        </span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-outline-secondary" style="min-width:80px">
            <i class="fas fa-chevron-left me-1" style="font-size:0.7rem"></i> Trước
        </a>
    @endif

    {{-- Số trang --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="btn btn-sm btn-outline-secondary disabled">...</span>
        @endif

        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="btn btn-sm btn-primary" style="min-width:36px">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="btn btn-sm btn-outline-secondary" style="min-width:36px">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Nút Tiếp --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-outline-secondary" style="min-width:80px">
            Tiếp <i class="fas fa-chevron-right ms-1" style="font-size:0.7rem"></i>
        </a>
    @else
        <span class="btn btn-sm btn-outline-secondary disabled" style="min-width:80px">
            Tiếp <i class="fas fa-chevron-right ms-1" style="font-size:0.7rem"></i>
        </span>
    @endif

</div>
@endif