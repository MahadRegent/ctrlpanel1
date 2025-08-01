@inject('vite', Vite::class)
@php
  $vite::useBuildDirectory('themes/phoenix');
  $website_settings = app(App\Settings\WebsiteSettings::class);
  $general_settings = app(App\Settings\GeneralSettings::class);
  $phoenixSettings = app(App\Extensions\Themes\Phoenix\PhoenixSettings::class);
@endphp

<!doctype html>
<html @if (!$phoenixSettings->force_theme_mode) :class="{ 'dark': dark }" @endif x-data="data"
  lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta content="{{ $website_settings->seo_title }}" property="og:title">
  <meta content="{{ $website_settings->seo_description }}" property="og:description">
  <meta
    content='{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('logo.png') ? asset('/logo.png') : asset('images/ctrlpanel_logo.png') }}'
    property="og:image">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'CtrlPanel') }}</title>
  <link rel="icon"
    href="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('favicon.ico') ? asset('storage/favicon.ico') : asset('favicon.ico') }}"
    type="image/x-icon">

  <style>
    [x-cloak] {
      display: none !important;
    }
  </style>
  @vite('js/pace.js')

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  @yield('head')

  @vite(['css/app.css', 'js/app.js', 'js/focus-trap.js'])
  <x-phoenix />

  @php($recaptchaVersion = app(App\Settings\GeneralSettings::class)->recaptcha_version)
  @if ($recaptchaVersion)
    @switch($recaptchaVersion)
      @case('v2')
        {!! htmlScriptTagJsApi() !!}
      @break

      @case('v3')
        {!! RecaptchaV3::initJs() !!}
      @break
    @endswitch
  @endif
</head>

<body class="dark:bg-gray-900">
  <div class="flex  items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900 dark:text-gray-300 text-gray-700">
    @yield('content')
  </div>
  <script>
    @if (Session::has('error'))
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        html: "{{ Session::get('error') }}",
      })
    @endif

    @if (Session::has('success'))
      Swal.fire({
        icon: 'success',
        title: "{{ Session::get('success') }}",
        position: 'top-end',
        showConfirmButton: false,
        background: '#343a40',
        toast: true,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
          toast.addEventListener('mouseenter', Swal.stopTimer)
          toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
      })
    @endif
  </script>
  @yield('scripts')

</body>

</html>
