@extends('layouts.main')
@section('content')
  <div class="container px-6 mx-auto">

    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
      {{ __('Dashboard') }}
    </h2>

    @if (!file_exists(base_path() . '/install.lock') && Auth::User()->hasRole('Admin'))
      <x-alert type="danger" title="The installer is not locked!">
        <p>
          {{ __('please create a file called "install.lock" in your dashboard Root directory. Otherwise no settings will be loaded!') }}
        </p>
        <a href="/install?step=7" class="underline">{{ __('or click here') }}</a>
      </x-alert>
    @endif


    @if ($general_settings->alert_enabled && !empty($general_settings->alert_message))
      <x-alert :type="$general_settings->alert_type" title="">
        <div class="noreset font-medium">
          {!! $general_settings->alert_message !!}
        </div>
      </x-alert>
    @endif

    <div
      class="grid gap-6 mb-8 @if ($credits > 0.01 and $usage > 0) md:grid-cols-2 xl:grid-cols-4 @else md:grid-cols-2 xl:grid-cols-3 @endif">
      <!-- Card -->
      <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
        <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
          <!-- heroicons icon -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
          </svg>
        </div>
        <div>
          <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
            {{ __('Servers') }}
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            {{ Auth::user()->servers()->count() }}
          </p>
        </div>
      </div>
      <!-- Card -->
      <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
        <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
              d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
              clip-rule="evenodd"></path>
          </svg>
        </div>
        <div>
          <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
            {{ $general_settings->credits_display_name }}
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            {{ Auth::user()->Credits() }}
          </p>
        </div>
      </div>
      <!-- Card -->
      <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
        <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
          <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path
              d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
            </path>
          </svg>
        </div>
        <div>
          <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
            {{ $general_settings->credits_display_name }} {{ __('Usage') }}
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            {{ number_format($usage, 2, '.', '') }}
            <sup>{{ __('per month') }}</sup>
          </p>
        </div>
      </div>

      @if ($credits > 0.01 && $usage > 0)
        <!-- Card -->
        <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
          <div
            class="p-3 mr-4 text-violet-500 bg-violet-100 rounded-full dark:text-violet-100 dark:bg-violet-500 {{ $bg }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div>
            <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
              {{ __('Out of Credits in', ['credits' => $general_settings->credits_display_name]) }}
            </p>
            <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
              {{ $boxText }}
              <sup>{{ $unit }}</sup>
            </p>
          </div>
        </div>
      @endif

    </div>

    @if ($website_settings->motd_enabled)
      <x-card title="{{ config('app.name', 'ControlPanel') }} - MOTD">
        <div class="unreset text-gray-700 dark:text-gray-200">
          {!! $website_settings->motd_message !!}
        </div>
      </x-card>
    @endif

    <div class="grid gap-6 mb-8 @if ($referral_settings->enabled || $website_settings->useful_links_enabled) md:grid-cols-3 @endif">
      <div class="w-full overflow-hidden col-span-2 md:col-span-1">
        @if ($website_settings->useful_links_enabled)
          <x-card title="Useful Links">
            @forelse ($useful_links_dashboard as $useful_link)
              <div class="mb-2">
                <h2 class="text-gray-700 dark:text-gray-200 font-semibold flex items-center">
                  <x-icon icon="{{ $useful_link->icon }}" height="22" width="22" class="mr-2"></x-icon>
                  <a class="underline" target="__blank" href="{{ $useful_link->link }}">
                    {{ $useful_link->title }}
                  </a>
                </h2>
                <div class="ml-8 mb-4 text-sm font-medium text-gray-600 dark:text-gray-400">
                  {!! $useful_link->description !!}
                </div>
              </div>
            @empty
              <span class="text-gray-600 dark:text-gray-400">{{ __('No useful links available') }}</span>
            @endforelse
          </x-card>
        @endif
        @if ($referral_settings->enabled)
          <x-card title="Partner Program">
            @if (Auth::user()->can('user.referral'))
              <div>
                <li
                  class="text-gray-700 dark:text-gray-200  relative block leading-normal bg-white dark:bg-gray-800 border-0 border-t-0 text-sm text-inherit">

                  <strong class="">Referral Code:</strong>
                  <span class="cursor-copy hover:text-gray-600 hover:dark:text-gray-400" data-content="Click to Copy URL"
                    data-toggle="popover" data-trigger="hover" data-placement="top"
                    onclick="onClickCopy('{{ route('register') }}?ref={{ Auth::user()->referral_code }}')">
                    {{ Auth::user()->referral_code }} (Click to Copy URL)</span>

                </li>

                <li
                  class="text-gray-700 dark:text-gray-200  relative block leading-normal bg-white dark:bg-gray-800 border-0 border-t-0 text-sm text-inherit">

                  <strong class="">Referred Users: </strong>
                  <span>{{ $numberOfReferrals }}</span>

                </li>
              </div>
              @if ($partnerDiscount)
                <div class="w-full overflow-x-auto rounded-lg shadow-sm ">
                  <table class="w-full whitespace-no-wrap">
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                      <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                          <div class="flex items-center text-sm">
                            <p class="font-semibold">
                              {{ __('Your discount') }}
                            </p>
                          </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                          {{ $partnerDiscount->partner_discount }}%
                        </td>
                      <tr>
                        <td class="px-4 py-3">
                          <div class="flex items-center text-sm">
                            <p class="font-semibold">
                              {{ __('Discount for your new users') }}
                            </p>
                          </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                          {{ $partnerDiscount->registered_user_discount }}%
                        </td>
                      </tr>
                      <tr>
                        <td class="px-4 py-3">
                          <div class="flex items-center text-sm">
                            <p class="font-semibold">
                              {{ __('Reward per registered user') }}
                            </p>
                          </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                          {{ $referral_settings->reward }}
                          {{ $general_settings->credits_display_name }}
                        </td>
                      </tr>
                      <tr>
                        <td class="px-4 py-3">
                          <div class="flex items-center text-sm">
                            <p class="font-semibold">
                              {{ __('New user payment commision') }}
                            </p>
                          </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                          {{ $partnerDiscount->referral_system_commission == -1 ? $referral_settings->percentage : $partnerDiscount->referral_system_commission }}%
                        </td>
                      </tr>
                      </tr>
                    </tbody>
                  </table>
                </div>
              @else
                <div class="w-full overflow-x-auto rounded-lg shadow-sm ">
                  <table class="w-full whitespace-no-wrap">
                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                      @if (in_array($referral_settings->mode, ['sign-up', 'both']))
                        <tr class="text-gray-700 dark:text-gray-400">
                          <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                              <p class="font-semibold">
                                {{ __('Reward per registered user') }}
                              </p>
                            </div>
                          </td>
                          <td class="px-4 py-3 text-sm">
                            {{ $referral_settings->reward }}
                            {{ $general_settings->credits_display_name }}
                          </td>
                        </tr>
                      @endif
                      @if (in_array($referral_settings->mode, ['commission', 'both']))
                        <tr>
                          <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                              <p class="font-semibold">
                                {{ __('New user payment commision') }}
                              </p>
                            </div>
                          </td>
                          <td class="px-4 py-3 text-sm">
                            {{ $referral_settings->percentage }}%
                          </td>
                        </tr>
                      @endif
                    </tbody>
                  </table>
                </div>
              @endif
            @else
              <span class="text-xs font-semibold leading-tight rounded-full badge-warning">
                {{ _('Make a purchase to reveal your referral-URL') }}</span>
            @endif
          </x-card>
        @endif
      </div>


      <div class="w-full overflow-hidden col-span-2">
        <h2 class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200">
          {{ __('Activity Logs') }}
        </h2>
        <div class="w-full overflow-x-auto rounded-lg shadow-sm ">
          <table class="w-full whitespace-no-wrap">
            <thead>
              <tr
                class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                <th class="px-4 py-3">{{ __('Log') }}</th>
                <th class="px-4 py-3">{{ __('Action') }}</th>
                <th class="px-4 py-3">{{ __('Time') }}</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              @forelse (Auth::user()->actions()->take(8)->orderBy('created_at', 'desc')->get() as $log)
                @php
                  $properties = json_decode($log->properties, true);
                @endphp
                <tr class="text-gray-700 dark:text-gray-200">
                  <td class="px-4 py-3 align-baseline w-full" x-data="{ description_open: false }">
                    <div class="text-sm font-semibold">
                      <div class="flex items-center gap-4">
                        <p>
                          {{ explode('\\', $log->subject_type)[2] }}
                          {{ ucfirst($log->description) }}
                        </p>
                        @if (array_key_exists('attributes', $properties) && count($properties['attributes']) > 0)
                          <button class="p-0.5 rounded-md dark:bg-gray-900 bg-gray-200"
                            @click="description_open = !description_open">
                            <svg class="w-5 h-5 transition-transform duration-200" aria-hidden="true"
                              fill="currentColor" viewBox="0 0 20 20" :class="{ 'rotate-180': description_open }">
                              <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                            </svg>
                          </button>
                        @endif
                      </div>

                      <div class="dark:text-gray-200 text-gray-600" x-cloak x-show="description_open"
                        x-transition:enter="transition-all ease-in-out duration-300"
                        x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-[59rem]"
                        x-transition:leave="transition-all ease-in-out duration-300"
                        x-transition:leave-start="opacity-100 max-h-[59rem]" x-transition:leave-end="opacity-0 max-h-0">
                        {{-- Handle Created Entries --}}
                        @if ($log->description === 'created' && isset($properties['attributes']))
                          <ul class="">
                            @foreach ($properties['attributes'] as $attribute => $value)
                              @if (!is_null($value))
                                <li>
                                  <span
                                    class="font-semibold dark:text-gray-400 text-gray-500">{{ ucfirst($attribute) }}:</span>
                                  {{ $attribute === 'created_at' || $attribute === 'updated_at' ? \Carbon\Carbon::parse($value)->toDayDateTimeString() : $value }}
                                </li>
                              @endif
                            @endforeach
                          </ul>
                        @endif

                        {{-- Handle Updated Entries --}}
                        @if ($log->description === 'updated' && isset($properties['attributes'], $properties['old']))
                          <ul class="">
                            @foreach ($properties['attributes'] as $attribute => $newValue)
                              @if (array_key_exists($attribute, $properties['old']) && !is_null($newValue))
                                <li>
                                  <span
                                    class="font-semibold dark:text-gray-400 text-gray-500">{{ ucfirst($attribute) }}:</span>
                                  {{ $attribute === 'created_at' || $attribute === 'updated_at'
                                      ? \Carbon\Carbon::parse($properties['old'][$attribute])->toDayDateTimeString() .
                                          ' ➜ ' .
                                          \Carbon\Carbon::parse($newValue)->toDayDateTimeString()
                                      : ($properties['old'][$attribute] ?: 'Null') . ' ➜ ' . $newValue }}
                                </li>
                              @endif
                            @endforeach
                          </ul>
                        @endif

                        {{-- Handle Deleted Entries --}}
                        @if ($log->description === 'deleted' && isset($properties['old']))
                          <ul class="">
                            @foreach ($properties['old'] as $attribute => $value)
                              @if (!is_null($value))
                                <li>
                                  <span
                                    class="font-semibold dark:text-gray-400 text-gray-500">{{ ucfirst($attribute) }}:</span>
                                  {{ $attribute === 'created_at' || $attribute === 'updated_at' ? \Carbon\Carbon::parse($value)->toDayDateTimeString() : $value }}
                                </li>
                              @endif
                            @endforeach
                          </ul>
                        @endif
                      </div>
                    </div>
                  </td>

                  <td class="px-4 py-3 align-baseline text-xs">
                    @if (str_starts_with($log->description, 'created'))
                      <span
                        class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:text-green-500 dark:bg-green-500/20">
                        Created
                      </span>
                    @elseif(str_starts_with($log->description, 'redeemed'))
                      <span
                        class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full dark:text-blue-500 dark:bg-blue-500/20">
                        Redeemed
                      </span>
                    @elseif(str_starts_with($log->description, 'deleted'))
                      <span
                        class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-500 dark:bg-red-500/20">
                        Deleted
                      </span>
                    @elseif(str_starts_with($log->description, 'gained'))
                      <span
                        class="px-2 py-1 font-semibold leading-tight text-lime-700 bg-lime-100 rounded-full dark:text-lime-500 dark:bg-lime-500/20">
                        Gained
                      </span>
                    @elseif(str_starts_with($log->description, 'updated'))
                      <span
                        class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 dark:text-yellow-500 dark:bg-yellow-500/20 rounded-full">
                        Updated
                      </span>
                    @endif
                  </td>


                  <td class="px-4 py-3 align-baseline text-sm whitespace-nowrap">
                    <x-tooltip :content="$log->created_at" html>
                      {{ $log->created_at->diffForHumans() }}
                    </x-tooltip>
                  </td>
                </tr>
              @empty
                <tr class="text-gray-700 dark:text-gray-200">
                  <td class="px-4 py-3 align-baseline w-full text-center" colspan="3">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('No activity logs available') }}</span>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
  <script>
    function onClickCopy(textToCopy) {
      if (navigator.clipboard) {
        navigator.clipboard.writeText(textToCopy).then(() => {
          Swal.fire({
            icon: 'success',
            title: '{{ __('URL copied to clipboard') }}',
            position: 'bottom-right',
            showConfirmButton: false,
            background: '#343a40',
            toast: true,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          })
        })
      } else {
        console.log('Browser Not compatible')
      }
    }
  </script>
@endsection
