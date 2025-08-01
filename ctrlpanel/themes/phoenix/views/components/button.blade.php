@props(['size' => 'normal', 'disabled' => false])

@php
  switch ($size) {
      case 'small':
          $padding = 'px-3 py-1 ';
          break;
      case 'normal':
      default:
          $padding = 'px-4 py-2 ';
          break;
      case 'big':
          $padding = 'px-5 py-3 ';
          break;
      case 'large':
          $padding = 'px-10 py-4 ';
          break;
  }
@endphp

<button @if ($disabled) disabled @endif
  {{ $attributes->merge(['type' => 'submit', 'class' => $padding . ' focus:ring focus:ring-primary-200 focus:ring-opacity-50 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-md focus:shadow-outline-purple active:bg-primary-600 hover:bg-primary-700 focus:shadow-outline-purple focus:outline-none disabled:pointer-events-none disabled:cursor-not-allowed']) }}>
  {{ $slot }}
</button>
