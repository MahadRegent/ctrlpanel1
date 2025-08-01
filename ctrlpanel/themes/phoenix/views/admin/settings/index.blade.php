@extends('layouts.main')

@inject('vite', Vite::class)
@php
  $vite::useBuildDirectory('themes/phoenix');
@endphp

@section('content')
  <x-container title="Settings">
    @if (!file_exists(base_path() . '/install.lock'))
      <x-alert type="danger" title="The installer is not locked!">
        <p>
          {{ __('please create a file called "install.lock" in your dashboard Root directory. Otherwise no settings will be loaded!') }}
        </p>
        <a href="/install?step=7" class="underline">{{ __('or click here') }}</a>
      </x-alert>
    @endif

    <div class="flex sm:flex-row flex-col gap-6 bg-white rounded-lg shadow-sm dark:bg-gray-800 mb-6"
      x-data="{ tab: window.location.hash.replace('#', '') || 'icons' }">
      <div class="sm:min-w-72 p-4 h-fit sm:sticky top-0">
        <ul>
          <li class="relative my-1 flex items-center group active">
            <a class="px-3 py-2 inline-flex items-center w-full text-base transition-colors duration-150 group-hover:text-gray-800 dark:group-hover:text-gray-200 rounded-md"
              :class="tab == 'icons' ? 'text-gray-800 dark:text-gray-100 dark:bg-gray-900 bg-gray-200' :
                  'dark:text-gray-400 text-gray-700'"
              href="#icons" x-on:click.prevent="tab = 'icons'; window.location.hash = '#icons'">

              <x-icon width="18px" height="18px" class="w-4 h-4 text-sm duration-500" ::class="tab == 'icons' ? 'dark:text-white text-gray-900' : ''"
                icon="ion:images"></x-icon>

              <span class="ml-4">
                {{ __('Images / Icons') }}
              </span>
            </a>
          </li>

          @foreach ($settings as $category => $options)
            @if (!str_contains($options['settings_class'], 'Extension'))
              @canany(['settings.' . strtolower($category) . '.read', 'settings.' . strtolower($category) . '.write'])
                <li class="relative my-1 flex items-center group active">
                  <a :class="tab == '{{ $category }}' ? 'text-gray-800 dark:text-gray-100 dark:bg-gray-900 bg-gray-200' :
                      'dark:text-gray-400 text-gray-700'"
                    class="px-3 py-2 inline-flex items-center w-full text-base transition-colors duration-150 group-hover:text-gray-800 dark:group-hover:text-gray-200 rounded-md"
                    href="#{{ $category }}"
                    x-on:click.prevent="tab = '{{ $category }}'; window.location.hash = '#{{ $category }}'">

                    <x-icon width="18px" height="18px" class="w-4 h-4 text-sm duration-500" ::class="tab == '{{ $category }}' ? 'dark:text-white text-gray-900' : ''"
                      icon="{{ str_starts_with($options['category_icon'], 'fas') ? str_replace('fas fa-', 'fa-solid:', $options['category_icon']) : $options['category_icon'] }}"></x-icon>

                    <span class="ml-4">{{ $category }}</span>
                  </a>
                </li>
              @endcanany
            @endif
          @endforeach

          @php
            $extensions = array_keys(
                array_filter($settings, function ($setting) {
                    return str_contains($setting['settings_class'], 'Extension');
                }),
            );
          @endphp

          <li class="relative my-1 group" x-data="{ open: {{ json_encode($extensions) }}.includes(window.location.hash.replace('#', '')) }">
            <div class="group-hover:text-gray-800 dark:group-hover:text-gray-200 rounded-md"
              :class="{ 'text-gray-800 dark:text-gray-100  ': open }">
              <button
                class="px-3 py-2 inline-flex items-center justify-between w-full text-base transition-colors duration-150 "
                @click="open = !open" aria-haspopup="true">

                <div class="flex items-center">

                  <x-icon width="18px" height="18px" class="w-4 h-4 duration-500" ::class="open ? 'dark:text-white text-gray-900' : ''"
                    icon="mingcute:pig-money-fill"></x-icon>

                  <span class="ml-4">{{ __('Extension Settings') }}</span>
                </div>

                <svg class="w-4 h-4 transition-transform duration-200" aria-hidden="true" fill="currentColor"
                  viewBox="0 0 20 20" :class="{ 'rotate-180': open }">
                  <path fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
                </svg>
              </button>

              <ul x-cloak x-show="open" x-transition:enter="transition-all ease-in-out duration-300"
                x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-[59rem]"
                x-transition:leave="transition-all ease-in-out duration-300"
                x-transition:leave-start="opacity-100 max-h-[59rem]" x-transition:leave-end="opacity-0 max-h-0"
                class="px-1 pb-4 overflow-hidden text-sm rounded-md text-gray-400" aria-label="submenu">
                @foreach ($settings as $category => $options)
                  @if (str_contains($options['settings_class'], 'Extension'))
                    @canany(['settings.' . strtolower($category) . '.read', 'settings.' . strtolower($category) .
                      '.write'])
                      <li class="">
                        <a :class="tab == '{{ $category }}' ? 'text-gray-800 dark:text-gray-100 font-semibold' : ''"
                          class="flex items-center px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 text-gray-600 dark:text-gray-300"
                          href="#{{ $category }}"
                          x-on:click.prevent="tab = '{{ $category }}'; window.location.hash = '#{{ $category }}'">
                          <span class="ml-8 w-full inline-block">{{ $category }}</span>
                        </a>
                      </li>
                    @endcanany
                  @endif
                @endforeach
              </ul>
            </div>
          </li>
        </ul>
      </div>
      <div class="w-full p-6 card">
        <div id="icons" role="tabpanel" x-show="tab == 'icons'">
          <form method="POST" enctype="multipart/form-data" class="mb-3"
            action="{{ route('admin.settings.updateIcons') }}">
            @csrf
            @method('POST')

            <x-validation-errors class="mb-4" :errors="$errors" />

            <div class="flex flex-col gap-8 mb-6">

              <div class="flex space-x-6">
                <div class="shrink-0">
                  <img id='favicon_preview' class="h-16 w-16 object-contain" src="{{ $images['favicon'] }}"
                    alt="FavIcon" />
                </div>
                <x-label title="FavIcon">
                  <x-input onchange="loadFile(event, 'favicon_preview')" type="file" accept="image/x-icon"
                    name="favicon" id="favicon" class="p-2"></x-input>
                </x-label>
              </div>

              <div class="flex space-x-6">
                <div class="shrink-0">
                  <img id='icon_preview' class="h-16 w-16 object-cover" src="{{ $images['icon'] }}" alt="Current icon" />
                </div>
                <x-label title="Icon">
                  <x-input onchange="loadFile(event, 'icon_preview')" id="icon" name="icon" type="file"
                    class="p-2" accept="image/png,image/jpeg,image/jpg,image/webp"></x-input>
                </x-label>
              </div>

              <div class="flex flex-col mr-auto">
                <div class="shrink-0">
                  <img id='logo_preview' class="h-auto max-w-sm object-contain w-full" src="{{ $images['logo'] }}"
                    alt="Current logo" />
                </div>
                <x-label title="Login-page Logo">
                  <x-input onchange="loadFile(event, 'logo_preview')" id="logo" name="logo" type="file"
                    class="p-2" accept="image/png,image/jpeg,image/jpg,image/webp"></x-input>
                </x-label>
              </div>
            </div>

            <x-button variant="normal" type="submit" class="min-w-24">
              {{ __('Save Changes') }}
            </x-button>
          </form>
        </div>
        @foreach ($settings as $category => $options)
          @canany(['settings.' . strtolower($category) . '.read', 'settings.' . strtolower($category) . '.write'])
            <div id="{{ $category }}" role="tabpanel" x-show="tab == '{{ $category }}'" x-cloak>
              <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('POST')
                <x-validation-errors class="mb-4" :errors="$errors" />

                <input type="hidden" name="settings_class" value="{{ $options['settings_class'] }}">
                <input type="hidden" name="category" value="{{ $category }}">

                @foreach ($options as $key => $option)
                  @if ($key == 'category_icon' || $key == 'settings_class' || $key == 'position')
                    @continue
                  @endif

                  @switch($option['type'])
                    @case('string')
                    @case('text')

                    @case('password')
                      @if ($option['identifier'] === 'color')
                        <x-label :title="$option['label']" :text="__($option['description'])">
                          <div class="flex gap-4">
                            <x-input class="coloris" :value="$option['value']" :id="$key" :name="$key"
                              :type="$option['type'] == 'string' ? 'text' : $option['type']"></x-input>
                            @if (array_key_exists('default', $option['options']))
                              <x-button type="button" size="small"
                                class="flex items-center justify-center dark:!border-gray-600 dark:!bg-gray-700 "
                                onclick="resetColorInput('#{{ $key }}', '{{ $option['options']['default'] }}')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                  <g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2">
                                    <path d="M9 14L4 9l5-5" />
                                    <path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11" />
                                  </g>
                                </svg>
                              </x-button>
                            @endif
                          </div>
                        </x-label>
                      @else
                        <x-label :title="$option['label']" :text="__($option['description'])">
                          <x-input :value="$option['value']" :id="$key" :name="$key" :type="$option['type'] == 'string' ? 'text' : $option['type']"
                            class=""></x-input>
                        </x-label>
                      @endif
                    @break

                    @case('number')
                      <x-label :title="$option['label']" :text="__($option['description'])">
                        <x-input :value="$option['value']" :id="$key" :name="$key" step="{{ $option['step'] ?? '1' }}"
                          type="number" class=""></x-input>
                      </x-label>
                    @break

                    @case('textarea')
                      <x-label :title="$option['label']" :text="__($option['description'])">
                        <x-textarea :id="$key" :name="$key" rows="3"
                          class="tinymce !border-gray-600 focus:!border-site-secondary ">{{ $option['value'] }}</x-textarea>
                      </x-label>
                    @break

                    @case('boolean')
                      <x-checkbox :title="$option['label']" :value="$option['value']" :id="$key" :name="$key"
                        :checked="$option['value'] ? true : false">
                        {{ __($option['description']) }}
                      </x-checkbox>
                    @break

                    @case('select')
                      <x-label :title="$option['label']" :text="__($option['description'])">
                        <x-select :id="$key" :name="$key" class="">
                          @if ($option['identifier'] == 'display')
                            @foreach ($option['options'] as $select_option => $display)
                              <option value="{{ $display }}" {{ $option['value'] == $display ? 'selected' : '' }}>
                                {{ __($display) }}
                              </option>
                            @endforeach
                          @else
                            @foreach ($option['options'] as $select_option => $display)
                              <option value="{{ $select_option }}"
                                {{ $option['value'] == $select_option ? 'selected' : '' }}>
                                {{ __($display) }}
                              </option>
                            @endforeach
                          @endif
                        </x-select>
                      </x-label>
                    @break

                    @case('multiselect')
                      <x-label :title="$option['label']" :text="__($option['description'])">
                        <x-select :id="$key . '[]'" :name="$key . '[]'" class=" custom-select" multiple>
                          @foreach ($option['options'] as $select_option)
                            <option value="{{ $select_option }}"
                              {{ strpos($option['value'], $select_option) !== false ? 'selected' : '' }}>
                              {{ __($select_option) }}
                            </option>
                          @endforeach
                        </x-select>
                      </x-label>
                    @break

                    @default
                  @endswitch
                @endforeach

                <div class="flex gap-6">
                  <x-button variant="danger-outline" type="reset" class="">{{ __('Reset') }}
                  </x-button>
                  <x-button variant="normal" type="submit" class="min-w-24">{{ __('Save Changes') }}
                  </x-button>
                </div>
              </form>
            </div>
          @endcanany
        @endforeach
      </div>
    </div>
  </x-container>

  <script>
    document.addEventListener('DOMContentLoaded', (event) => {
      $('.custom-select').select2();

      tinymce.init({
        selector: 'textarea.tinymce',
        skin: "Phoenix",
        promotion: false,
        // content_css: "Phoenix",
        skin_url: "/themes/phoenix/tinymce",
        body_class: 'tinymce-phoenix',
        content_css: "/themes/phoenix/tinymce/content.min.css",
        branding: false,
        height: 500,
        plugins: ['image', 'link'],
      });
    })
  </script>
@endsection

@section('head')
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
@endsection

@section('scripts')
  <script>
    const loadFile = function(event, output_elm) {

      const input = event.target;
      const file = input.files[0];
      const type = file.type;

      const output = document.getElementById(output_elm);
      output.src = URL.createObjectURL(event.target.files[0]);
      output.onload = function() {
        URL.revokeObjectURL(output.src) // free memory
      }
    };

    const resetColorInput = function(element_id, color) {
      const input = document.querySelector(element_id);
      input.value = color;
      input.dispatchEvent(new Event('input', {
        bubbles: true
      }));
    }
  </script>
  <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
  @vite(['js/coloris.js', 'js/tinymce.js'])
@endsection
