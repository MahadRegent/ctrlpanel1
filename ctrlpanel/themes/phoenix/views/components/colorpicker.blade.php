@props(['name' => '', 'value', 'disabled' => false])

<div class="relative w-full mt-1">
  <input value="{{ $value }}" type="text" name="{{ $name }}" {{ $disabled ? 'disabled' : '' }}
    @error($name) {!! $attributes->merge([
        'class' =>
            'coloris border-red-600 block w-full text-sm dark:bg-gray-700 focus:border-red-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-red rounded-md shadow-sm focus:ring focus:ring-red-200 focus:ring-opacity-50',
    ]) !!}
          @else
      {!! $attributes->merge([
          'class' =>
              'coloris block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-primary-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray rounded-md shadow-sm border-gray-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50',
      ]) !!} @enderror>
</div>
