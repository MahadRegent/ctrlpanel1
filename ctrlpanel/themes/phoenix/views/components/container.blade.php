@props([
    'title',
    'btnLink' => '',
    'btnText' => '',
    'btnPermission' => '',
    'raw_title' => false,
    'description' => null,
    'actions' => null,
])

<div {{ $attributes->merge(['class' => 'container p-6 mx-auto grid']) }}>
  <div class="flex justify-between mb-6 flex-row">
    <div>
      <h2
        class="{{ isset($description) && $btnText ? 'mb-2' : '' }} text-xl font-semibold text-gray-700 dark:text-gray-200">
        @if ($raw_title)
          {{ $title }}
        @else
          {{ __($title) }}
        @endif
      </h2>
      @if (isset($description))
        <p class="-mt-2 mb-4 text-gray-200">{{ $description }}</p>
      @endif
    </div>
    @if ($btnLink && $btnText && !$actions)
      @can($btnPermission)
        <a href="{{ $btnLink }}">
          <x-button variant="outline" size="small">{{ __($btnText) }}</x-button>
        </a>
      @endcan
    @else
      {{ $actions }}
    @endif
  </div>
  {{ $slot }}
</div>
