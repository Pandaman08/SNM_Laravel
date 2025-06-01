@if ($paginator->hasPages())
    <!-- Paginación -->
    <div class="flex justify-center mt-8">
        <nav class="inline-flex shadow rounded-md overflow-hidden" aria-label="Pagination">
          

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

           
        </nav>
    </div>
@endif
