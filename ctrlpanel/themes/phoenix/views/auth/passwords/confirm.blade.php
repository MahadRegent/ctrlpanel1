@extends('layouts.app')

@section('content')
  @php($website_settings = app(App\Settings\WebsiteSettings::class))

  <div class="mx-auto">

    @if ($website_settings->enable_login_logo)
      <div
        class="md:hidden flex gap-4 items-center justify-center mb-6 text-2xl font-bold text-gray-800 dark:text-gray-200">
        @if (\Illuminate\Support\Facades\Storage::disk('public')->exists('icon.png'))
          <img
            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('icon.png') ? asset('storage/icon.png') : asset('images/ctrlpanel_logo.png') }}"
            alt="{{ config('app.name', 'ControlPanel.gg') }} Logo" class="rounded-lg w-[4.5rem] h-[4.5rem]">
        @endif
        <span>
          {{ config('app.name', 'ControlPanel') }}
        </span>
      </div>
    @endif

    <div
      class="{{ ($website_settings->enable_login_logo ? 'max-w-4xl ' : 'max-w-[40rem] ') . 'flex-1 h-full  mx-auto overflow-hidden bg-white rounded-lg shadow-xl dark:bg-gray-800' }}">
      <div class="flex flex-col overflow-y-auto md:flex-row">
        @if ($website_settings->enable_login_logo)
          <div class="h-32 md:h-auto md:w-1/2 hidden md:block">
            <img aria-hidden="true" class="object-cover w-full h-full"
              src="{{ \Illuminate\Support\Facades\Storage::disk('public')->exists('logo.png') ? \Illuminate\Support\Facades\Storage::url('logo.png') : asset('images/placeholder.jpeg') }}"
              alt="{{ config('app.name', 'Controlpanel.gg') }} Logo" />
          </div>
        @endif
        <div class="{{ $website_settings->enable_login_logo ? 'md:w-1/2 ' : 'w-full ' }}">
          <div class="w-full p-6 sm:p-12 ">

            <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
              {{ __('Confirm Password') }}
            </h1>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />

            <p class="mb-4">
              {{ __('Please confirm your password before continuing.') }}
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
              @csrf

              <label class="block text-sm">
                <span class="text-gray-700 dark:text-gray-400">{{ __('New Password') }}</span>
                <x-input type="password" name="password" placeholder="***************" required aria-autocomplete="off" />

              </label>

              <button
                class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-lg active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-purple"
                type="submit">
                {{ __('Confirm Password') }}
              </button>
            </form>

            @if (Route::has('password.request'))
              <hr class="my-8 border-gray-300 dark:border-gray-600" />
              <p class="mt-4">
                <a class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline"
                  href="{{ route('password.request') }}">
                  {{ __('Forgot Your Password?') }}
                </a>

              </p>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
