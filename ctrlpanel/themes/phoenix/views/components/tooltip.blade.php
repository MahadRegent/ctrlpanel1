@props(['content' => '', 'html' => false, 'pos' => 'top'])

@php
  switch ($pos) {
      case 'top':
          $pos_classes = 'bottom-full left-1/2 -translate-x-[50%]';
          $arrow = 'left-0 top-full';
          break;

      case 'bottom':
          $pos_classes = 'top-[115%] left-1/2 -translate-x-[50%]';
          $arrow = 'left-0 bottom-full rotate-180 ';
          break;
      case 'left':
          $pos_classes = 'right-[115%] top-1/2 -translate-y-[50%]';
          $arrow = 'right-0 left-[calc(50%_+_4px)] bottom-1/2 translate-y-[50%] rotate-[270deg]';
          break;
      default:
          # code...
          break;
  }
@endphp

@if ($content)
  <div
    {{ $attributes->merge(['class' => 'group cursor-pointer relative inline-block border-b border-gray-400 text-center']) }}>
    {{ $slot }}
    <div
      class="font-bold opacity-0 dark:bg-gray-700 bg-gray-200 transition-opacity dark:text-white text-gray-700 text-center text-xs rounded-lg py-2 absolute z-20 group-hover:opacity-100 group-focus-within:opacity-100 px-3 pointer-events-none w-max {{ $pos_classes }}">
      @if ($html)
        {!! $content !!}
      @else
        {{ __($content) }}
      @endif
      <svg class="absolute dark:text-gray-700 text-gray-200 h-2 w-full {{ $arrow }}" x="0px" y="0px"
        viewBox="0 0 255 255" xml:space="preserve">
        <polygon class="fill-current" points="0,0 127.5,127.5 255,0" />
      </svg>
    </div>
  </div>
@else
  {{ $slot }}
@endif
