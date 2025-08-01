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

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  @yield('head')

  @vite('css/app.css')

  <x-phoenix />
</head>

<body class="dark:bg-gray-900">
  <div class="flex items-center min-h-screen p-6 bg-gray-50 dark:bg-gray-900 dark:text-gray-300 text-gray-700">

    <div
      class="flex-1 h-full max-w-md mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800 text-center p-12">
      <h1 class="mb-4 text-xl font-semibold text-red-600 dark:text-red-500">
        {{ __('ERROR') }} {{ $errorCode }} - {{ $title }}
      </h1>

      <hr class="my-4 border-gray-300 dark:border-gray-600" />

      <p class="mb-12">
        {{ $message }}
      </p>

      @if ($homeLink ?? false)
        <a class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-lg active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-purple"
          href="{{ route('home') }}">
          {{ __('Go home') }}
        </a>
      @endif
    </div>
  </div>
  @yield('scripts')
</body>

</html>
