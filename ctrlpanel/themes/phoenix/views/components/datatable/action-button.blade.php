@props([
    'name' => '',
    'raw' => false,
    'icon' => '',
    'type' => 'success',
    'form' => false,
    'action' => '',
    'method' => 'POST',
    'form_attributes' => [],
])

@if ($form)
  <form class="contents" method="POST" action="{{ $action }}"
    @foreach ($form_attributes as $attribute => $value)
        {{ $attribute }}="{{ $value }}" @endforeach>
    @csrf
    @method($method)
    <x-tooltip :content="$name" :html="$raw" class="border-none">
      <button
        class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray">
        <x-icon :icon="$icon" height="24" width="24"></x-icon>
      </button>
    </x-tooltip>
  </form>
@else
  <x-tooltip :content="$name" :html="$raw" class="border-none">
    <a href="{{ $action }}"
      class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 text-red-600 rounded-lg dark:text-gray-400 focus:outline-none focus:shadow-outline-gray">
      <x-icon :icon="$icon" height="24" width="24"></x-icon>
    </a>
  </x-tooltip>
@endif
