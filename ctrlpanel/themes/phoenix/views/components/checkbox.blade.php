@props([
    'title',
    'disabled' => false,
    'name' => '',
    'checked' => false,
    'id' => '',
    'value' => true,
    'onchange' => '',
])

<label {{ $attributes->merge(['class' => 'block mb-4 font-medium text-sm text-gray-700']) }}>
  <div class="flex items-center gap-2">

    <input onchange="{{ $onchange }}" @if ($checked) checked @endif type="checkbox"
      name="{{ $name }}" id="{{ $id }}" value="{{ $value }}" {{ $disabled ? 'disabled' : '' }}
      @error($name)
                {!! $attributes->merge([
                    'class' =>
                        'text-primary-600 form-checkbox focus:border-primary-400 focus:outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded ml-4',
                ]) !!}
            @else
                {!! $attributes->merge([
                    'class' =>
                        'text-primary-600 form-checkbox focus:border-primary-400 focus:outline-none focus:ring focus:ring-primary-200 focus:ring-opacity-50 rounded',
                ]) !!}
            @enderror>
    <span class="text-gray-700 dark:text-gray-400">
      {{ __($title) }}
    </span>
  </div>
  <span class="text-xs text-gray-600 dark:text-gray-400/60">
    {{ $slot }}
  </span>
</label>
