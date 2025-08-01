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
            <!-- Validation Errors -->
            <x-validation-errors class="mb-4" :errors="$errors" />
            @if ($errors->has('ptero_registration_error'))
              @foreach ($errors->get('ptero_registration_error') as $err)
                <div class="font-medium text-sm text-red-600 mb-4">
                  {{ $err }}
                </div>
              @endforeach
            @endif

            @if (!app(App\Settings\UserSettings::class)->creation_enabled)
              <x-alert title="The system administrator has blocked the registration of new users!" type="info">
              </x-alert>
            @else
              <h1 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
                {{ __('Create Account') }}
              </h1>

              <form method="POST" action="{{ route('register') }}" class="mt-4">
                @csrf

                <label class="block text-sm">
                  <span class="text-gray-700 dark:text-gray-400">{{ __('Username') }}</span>
                  <x-input placeholder="{{ __('Username') }}" type="text" id="name" name="name"
                    value="{{ old('name') }}" required autofocus />

                </label>
                <label class="block mt-4 text-sm">
                  <span class="text-gray-700 dark:text-gray-400">{{ __('Email') }}</span>
                  <x-input type="email" name="email" id="email" value="{{ old('email') }}"
                    placeholder="{{ __('foo@gmail.com') }}" required />

                </label>
                <div class="flex gap-4 mt-4">
                  <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">{{ __('Password') }}</span>
                    <x-input type="password" name="password" placeholder="***************" required />

                  </label>
                  <label class="block text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                      {{ __('Confirm password') }}
                    </span>
                    <x-input type="password" name="password_confirmation" placeholder="***************" required />

                  </label>
                </div>
                @if (app(App\Settings\ReferralSettings::class)->enabled)
                  <label class="block mt-4 text-sm">
                    <span class="text-gray-700 dark:text-gray-400">
                      {{ __('Referral Code (optional)') }}
                    </span>
                    <x-input placeholder="" type="text" name="referral_code" value="{{ \Request::get('ref') }}" />

                  </label>
                @endif

                @php($recaptchaVersion = app(App\Settings\GeneralSettings::class)->recaptcha_version)
                @if ($recaptchaVersion)
                  <div class="block mt-6 text-sm">
                    @switch($recaptchaVersion)
                      @case('v2')
                        {!! htmlFormSnippet() !!}
                      @break

                      @case('v3')
                        {!! RecaptchaV3::field('recaptchathree') !!}
                      @break
                    @endswitch
                  </div>
                @endif

                @php($website_settings = app(App\Settings\WebsiteSettings::class))
                @if ($website_settings->show_tos)
                  <div class="flex mt-6 text-sm">
                    <label class="flex items-center dark:text-gray-400">
                      <input name="terms" value="agree" type="checkbox"
                        class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50">
                      <span class="ml-2">
                        {{ __('I agree to the') }}
                        <a target="_blank" href="{{ route('terms', 'tos') }}">{{ __('Terms of Service') }}</a>

                      </span>
                    </label>
                  </div>
                @endif

                <button
                  class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-lg active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-purple"
                  type="submit">
                  {{ __('Create account') }}
                </button>

              </form>
            @endif

            <hr class="my-8 border-gray-300 dark:border-gray-600" />

            <p class="mt-4">
              <a class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline"
                href="{{ route('login') }}">
                {{ __('Already registered? login') }}
              </a>
            </p>
          </div>
          <x-information />
        </div>
      </div>
    </div>
  </div>
@endsection
