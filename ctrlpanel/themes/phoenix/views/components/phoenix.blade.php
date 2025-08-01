@php
  function hexToRgb($hex, $alpha = false)
  {
      $hex = str_replace('#', '', $hex);
      $length = strlen($hex);
      $r = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
      $g = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
      $b = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
      return "$r $g $b";
  }

  $localeSettings = app(App\Settings\LocaleSettings::class);
  $phoenixSettings = app(App\Extensions\Themes\Phoenix\PhoenixSettings::class);
@endphp

<style>
  :root {
    --primary-100: {{ hexToRgb($phoenixSettings->primary_100) }};
    --primary-200: {{ hexToRgb($phoenixSettings->primary_200) }};
    --primary-300: {{ hexToRgb($phoenixSettings->primary_300) }};
    --primary-400: {{ hexToRgb($phoenixSettings->primary_400) }};
    --primary-500: {{ hexToRgb($phoenixSettings->primary_500) }};
    --primary-600: {{ hexToRgb($phoenixSettings->primary_600) }};
    --primary-700: {{ hexToRgb($phoenixSettings->primary_700) }};

    --gray-50: {{ hexToRgb($phoenixSettings->gray_50) }};
    --gray-100: {{ hexToRgb($phoenixSettings->gray_100) }};
    --gray-200: {{ hexToRgb($phoenixSettings->gray_200) }};
    --gray-300: {{ hexToRgb($phoenixSettings->gray_300) }};
    --gray-400: {{ hexToRgb($phoenixSettings->gray_400) }};
    --gray-500: {{ hexToRgb($phoenixSettings->gray_500) }};
    --gray-600: {{ hexToRgb($phoenixSettings->gray_600) }};
    --gray-700: {{ hexToRgb($phoenixSettings->gray_700) }};
    --gray-800: {{ hexToRgb($phoenixSettings->gray_800) }};
    --gray-900: {{ hexToRgb($phoenixSettings->gray_900) }};
  }
</style>

<script>
  const default_theme = @json($phoenixSettings->default_theme);
  const force_theme_mode = @json($phoenixSettings->force_theme_mode);

  function getThemeFromLocalStorage() {

    if (force_theme_mode) {
      return force_theme_mode;
    }

    // if user already changed the theme, use it
    if (window.localStorage.getItem('theme')) {
      return window.localStorage.getItem('theme')
    }

    let theme = default_theme === 'system' ? (window.matchMedia("(prefers-color-scheme: dark)").matches ? 'dark' :
        'light') :
      default_theme;

    // else, return default theme
    return theme;
  }

  function setThemeToLocalStorage(value) {
    window.localStorage.setItem('theme', value)
  }

  const setTheme = (theme) => {
    theme ??= getThemeFromLocalStorage();
    if (theme === 'dark') document.documentElement.classList.add('dark');
  };
  setTheme();

  window.addEventListener('load', () => {
    if ($.fn.dataTable) {
      $.extend($.fn.dataTable.defaults, {
        language: {
          url: '//cdn.datatables.net/plug-ins/2.0.7/i18n/{{ $localeSettings->datatables }}.json',
          processing: `
              <div class="w-full h-full flex items-center justify-center z-10">
              <svg class="w-10 h-10 animate-spin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><defs><linearGradient id="mingcuteLoadingFill0" x1="50%" x2="50%" y1="5.271%" y2="91.793%"><stop offset="0%" stop-color="currentColor"/><stop offset="100%" stop-color="currentColor" stop-opacity="0.55"/></linearGradient><linearGradient id="mingcuteLoadingFill1" x1="50%" x2="50%" y1="15.24%" y2="87.15%"><stop offset="0%" stop-color="currentColor" stop-opacity="0"/><stop offset="100%" stop-color="currentColor" stop-opacity="0.55"/></linearGradient></defs><g fill="none"><path d="m12.593 23.258l-.011.002l-.071.035l-.02.004l-.014-.004l-.071-.035q-.016-.005-.024.005l-.004.01l-.017.428l.005.02l.01.013l.104.074l.015.004l.012-.004l.104-.074l.012-.016l.004-.017l-.017-.427q-.004-.016-.017-.018m.265-.113l-.013.002l-.185.093l-.01.01l-.003.011l.018.43l.005.012l.008.007l.201.093q.019.005.029-.008l.004-.014l-.034-.614q-.005-.018-.02-.022m-.715.002a.02.02 0 0 0-.027.006l-.006.014l-.034.614q.001.018.017.024l.015-.002l.201-.093l.01-.008l.004-.011l.017-.43l-.003-.012l-.01-.01z"/><path fill="url(#mingcuteLoadingFill0)" d="M8.749.021a1.5 1.5 0 0 1 .497 2.958A7.5 7.5 0 0 0 3 10.375a7.5 7.5 0 0 0 7.5 7.5v3c-5.799 0-10.5-4.7-10.5-10.5C0 5.23 3.726.865 8.749.021" transform="translate(1.5 1.625)"/><path fill="url(#mingcuteLoadingFill1)" d="M15.392 2.673a1.5 1.5 0 0 1 2.119-.115A10.48 10.48 0 0 1 21 10.375c0 5.8-4.701 10.5-10.5 10.5v-3a7.5 7.5 0 0 0 5.007-13.084a1.5 1.5 0 0 1-.115-2.118" transform="translate(1.5 1.625)"/></g></svg>
              </div>
              `
        },

        processing: true,
        serverSide: true,
        stateSave: true,
        paging: true,
        bInfo: true,
        columnDefs: [{
          className: "px-4 py-3",
          "targets": "_all"
        }],
        fnDrawCallback: function(oSettings) {
          $('[data-toggle="popover"]').popover();
        }
      });
    }
  });
</script>
