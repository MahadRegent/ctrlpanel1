@props(['icon'])

@php
  switch ($icon) {
      case str_starts_with($icon, 'fas'):
          $icon = str_replace('fas fa-', 'fa-solid:', $icon);
          break;
      case str_starts_with($icon, 'far'):
          $icon = str_replace('far fa-', 'fa-regular:', $icon);
          break;
      case str_starts_with($icon, 'fab'):
          $icon = str_replace('fab fa-', 'fa6-brands:', $icon);
          break;
      default:
          break;
  }
@endphp

<iconify-icon icon="{{ $icon }}" {{ $attributes }}></iconify-icon>
