@if ($paginator->hasPages())
<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-4">

    {{-- Record Count --}}
    <p class="text-xs text-gray-500 font-medium order-2 sm:order-1">
        @if ($paginator->firstItem())
            Menampilkan <span class="font-bold text-gray-700">{{ $paginator->firstItem() }}</span>–<span class="font-bold text-gray-700">{{ $paginator->lastItem() }}</span>
            dari <span class="font-bold text-gray-700">{{ $paginator->total() }}</span> data
        @else
            Tidak ada data
        @endif
    </p>

    {{-- Page Links --}}
    <div class="flex items-center gap-1 order-1 sm:order-2">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-300 bg-gray-50 border border-gray-100 cursor-not-allowed text-sm font-bold select-none">‹</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-500 bg-white border border-gray-200 hover:border-primary hover:text-primary hover:bg-green-50 transition-all text-sm font-bold shadow-sm">‹</a>
        @endif

        {{-- Page Numbers --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-400 text-sm font-bold select-none">…</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-primary text-white text-sm font-bold shadow-md shadow-primary/20 cursor-default" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-600 bg-white border border-gray-200 hover:border-primary hover:text-primary hover:bg-green-50 transition-all text-sm font-semibold shadow-sm">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-500 bg-white border border-gray-200 hover:border-primary hover:text-primary hover:bg-green-50 transition-all text-sm font-bold shadow-sm">›</a>
        @else
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-gray-300 bg-gray-50 border border-gray-100 cursor-not-allowed text-sm font-bold select-none">›</span>
        @endif
    </div>

</nav>
@endif
