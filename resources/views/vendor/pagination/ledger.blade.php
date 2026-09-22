@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between sm:justify-end gap-2 text-[12px] text-[#6B5D45] pt-4">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="px-2.5 py-1.5 text-[#9C8E76] cursor-not-allowed inline-flex items-center gap-1" aria-disabled="true">
                <x-bi-chevron-left class="w-3.5 h-3.5" />
                <span>Prev</span>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-2.5 py-1.5 hover:text-[#3D3428] inline-flex items-center gap-1 transition-colors">
                <x-bi-chevron-left class="w-3.5 h-3.5" />
                <span>Prev</span>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="inline-flex items-center gap-1">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-2 py-1 text-[#9C8E76]">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-2.5 py-1 font-semibold text-[#C9A84C] bg-[#FFFDF7] border border-[#D4C5A9] rounded" aria-current="page">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-2.5 py-1 hover:text-[#3D3428] transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-2.5 py-1.5 hover:text-[#3D3428] inline-flex items-center gap-1 transition-colors">
                <span>Next</span>
                <x-bi-chevron-right class="w-3.5 h-3.5" />
            </a>
        @else
            <span class="px-2.5 py-1.5 text-[#9C8E76] cursor-not-allowed inline-flex items-center gap-1" aria-disabled="true">
                <span>Next</span>
                <x-bi-chevron-right class="w-3.5 h-3.5" />
            </span>
        @endif
    </nav>
@endif
