@if ($paginator->hasPages())
  <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex flex-col items-center mb-6">

    {{-- Mobile --}}
    <div class="sm:hidden flex items-center justify-between w-full">
      {{-- Previous Page Link --}}
      @if ($paginator->onFirstPage())
        <button disabled aria-label="{{ __('pagination.previous') }}"
          class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-md hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
            viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 5H1m0 0 4 4M1 5l4-4" />
          </svg>
        </button>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
          class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-md hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 5H1m0 0 4 4M1 5l4-4" />
          </svg>
        </a>
      @endif

      <!-- Help text -->
      <span class="text-sm text-gray-700 dark:text-gray-400">
        {!! __('Showing') !!}
        @if ($paginator->firstItem())
          <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
          {!! __('to') !!}
          <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
        @else
          {{ $paginator->count() }}
        @endif
        {!! __('of') !!}
        <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
        {!! __('results') !!}
      </span>

      @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
          class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-md hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M1 5h12m0 0L9 1m4 4L9 9" />
          </svg>
        </a>
      @else
        <button disabled aria-label="{{ __('pagination.next') }}"
          class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-md hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
          <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="none" viewBox="0 0 14 10">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M1 5h12m0 0L9 1m4 4L9 9" />
          </svg>
        </button>
      @endif
    </div>

    <div class="hidden sm:flex flex-col items-center">
      <!-- Help text -->
      <span class="text-sm text-gray-700 dark:text-gray-400">
        {!! __('Showing') !!}
        @if ($paginator->firstItem())
          <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->firstItem() }}</span>
          {!! __('to') !!}
          <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->lastItem() }}</span>
        @else
          {{ $paginator->count() }}
        @endif
        {!! __('of') !!}
        <span class="font-semibold text-gray-900 dark:text-white">{{ $paginator->total() }}</span>
        {!! __('results') !!}
      </span>
      <div class="inline-flex mt-2 xs:mt-0">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
          <button disabled aria-label="{{ __('pagination.previous') }}"
            class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-l-md hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 5H1m0 0 4 4M1 5l4-4" />
            </svg>
            {{-- {!! __('pagination.previous') !!} --}}
          </button>
        @else
          <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}"
            class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 rounded-l-md hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            <svg class="w-3.5 h-3.5 me-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M13 5H1m0 0 4 4M1 5l4-4" />
            </svg>
            {{-- {!! __('pagination.previous') !!} --}}
          </a>
        @endif
        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
          {{-- "Three Dots" Separator --}}
          @if (is_string($element))
            <button disabled
              class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 border-0 border-s border-gray-700  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
              {{ $element }}
            </button>
          @endif
          {{-- Array Of Links --}}
          @if (is_array($element))
            @foreach ($element as $page => $url)
              @if ($page == $paginator->currentPage())
                <button disabled aria-current="page"
                  class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 border-0 border-s border-gray-700  dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">
                  {{ $page }}
                </button>
              @else
                <a href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                  class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 border-0 border-s border-gray-700 hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                  {{ $page }}
                </a>
              @endif
            @endforeach
          @endif
        @endforeach
        {{-- Next page links --}}
        @if ($paginator->hasMorePages())
          <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}"
            class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-r-md hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            {{-- {!! __('') !!} --}}
            <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M1 5h12m0 0L9 1m4 4L9 9" />
            </svg>
          </a>
        @else
          <button disabled aria-label="{{ __('pagination.next') }}"
            class="flex items-center justify-center px-3 h-8 text-sm font-medium text-white bg-gray-800 border-0 border-s border-gray-700 rounded-r-md hover:bg-gray-900 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            {{-- {!! __('pagination.next') !!} --}}
            <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
              fill="none" viewBox="0 0 14 10">
              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M1 5h12m0 0L9 1m4 4L9 9" />
            </svg>
          </button>
        @endif
      </div>
    </div>
  </nav>
@endif
