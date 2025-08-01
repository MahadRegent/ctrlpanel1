@inject('vite', Vite::class)
@php
  $vite::useBuildDirectory('themes/phoenix');
  $website_settings = app(App\Settings\WebsiteSettings::class);
  $general_settings = app(App\Settings\GeneralSettings::class);
  $phoenixSettings = app(App\Extensions\Themes\Phoenix\PhoenixSettings::class);
@endphp

<!DOCTYPE html>
<html @if (!$phoenixSettings->force_theme_mode) :class="{ 'dark': dark }" @endif x-data="data"
  lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html">

<head>

  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta content="{{ $website_settings->seo_title }}" property="og:title">
  <meta content="{{ $website_settings->seo_description }}" property="og:description">
  <meta
    content='{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('logo.png') ? asset('storage/logo.png') : asset('images/ctrlpanel_logo.png') }}'
    property="og:image">

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

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

  <script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  @yield('head')

  @vite(['css/app.css', 'js/app.js', 'js/focus-trap.js'])
  <x-phoenix />
</head>

<body class="dark:bg-gray-900">
  <div class="pointer-events-auto flex h-screen bg-gray-50 dark:bg-gray-900"
    :class="{ 'overflow-hidden': isSideMenuOpen }">
    @include('includes.desktop-sidebar')

    @include('includes.mobile-sidebar')

    <div class="flex flex-col flex-1 w-full">
      @include('includes.header')
      <main class="flex flex-col justify-between h-full overflow-y-auto text-gray-700 dark:text-gray-400">
        <div>

          {{-- @if (!Auth::user()->hasVerifiedEmail())
            @if (Auth::user()->created_at->diffInHours(now(), false) > 1)
              <x-alert title="You have not yet verified your email address!" type="warning" class="mt-6 mb-0">
                <p>
                  <a class="text-primary underline"
                    href="{{ route('verification.send') }}">{{ __('Resend verification email') }}</a>
                  <br>
                  {{ __('Please contact support If you didnt receive your verification email.') }}
                </p>
              </x-alert>
            @endif
          @endif --}}

          @yield('content')
        </div>

        <footer
          class="p-4 mt-4 bg-white shadow-md dark:bg-gray-800 flex flex-col sm:flex-row justify-between items-center">
          <div>
            Авторские права &copy; {{ date('Y') }} <a
              href="{{ url('/') }}">{{ env('APP_NAME', 'CtrlPanel') }}</a>.
            Все права защищены.
          </div>
          <div>
            <x-information />
          </div>
        </footer>
      </main>
    </div>
  </div>
  @include('models.redeem_voucher_modal')

  <script>
    window.addEventListener("load", (event) => {
      const selects = document.querySelectorAll('.nice-select');
      selects.forEach(select_elm => {
        new NiceSelect(select_elm);
      });
    });

    $(document).ready(function() {
      $('[data-toggle="popover"]').popover();

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
    });
  </script>
  <script>
    window.addEventListener('load', () => {

      @if (Session::has('error'))
        Swal.fire({
          icon: 'error',
          title: 'Упс...',
          background: '#343a40',
          html: '{{ Session::get('error') }}',
        })
      @endif
      @if (Session::has('success'))
        Swal.fire({
          icon: 'success',
          title: '{{ Session::get('success') }}',
          position: 'bottom-end',
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
      @if (Session::has('info'))
        Swal.fire({
          icon: 'info',
          title: '{{ Session::get('info') }}',
          position: 'bottom-end',
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
      @if (Session::has('warning'))
        Swal.fire({
          icon: 'warning',
          title: '{{ Session::get('warning') }}',
          position: 'bottom-end',
          showConfirmButton: false,
          toast: true,
          timer: 3000,
          timerProgressBar: true,
          didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
          }
        })
      @endif
    });
  </script>
  @vite('js/iconify-icon.js')
  @yield('scripts')

</body>

</html>
