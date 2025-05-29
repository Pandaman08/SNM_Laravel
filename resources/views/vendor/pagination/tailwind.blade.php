@if ($paginator->hasPages())
    <!-- Paginación -->
    <div class="flex justify-center mt-8">
        <nav class="inline-flex shadow rounded-lg overflow-hidden" aria-label="Pagination">
            <!-- Botón "Anterior" -->
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif

            <!-- Números de página -->
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-4 py-2 bg-gray-100 text-gray-700">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-4 py-2 bg-blue-500 text-white font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-blue-500 hover:text-white transition font-medium">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            <!-- Botón "Siguiente" -->
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="px-4 py-2 bg-gray-100 text-gray-500 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </nav>
    </div>
@endif
