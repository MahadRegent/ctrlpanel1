@extends('layouts.main')

@section('content')
  <x-container title="Activity Logs">

    @if ($cronlogs)
      <x-alert title="Cron Jobs are running!" type="success">{{ $cronlogs }}</x-alert>
    @else
      <x-alert title="No recent activity from cronjobs!" type="danger">{{ __('Are cronjobs running?') }}
        <br /><a class="text-primary underline" target="_blank"
          href="https://ctrlpanel.gg/docs/Installation/getting-started#crontab-configuration">{{ __('Check the docs for it here') }}</a>
      </x-alert>
    @endif

    <form method="get" action="{{ route('admin.activitylogs.index') }}">
      @csrf
      <x-label title="Search logs from user">
        <x-input value="{{ \Request::get('search') }}" name="search" placeholder="" type="text" autofocus>
          <x-button>{{ __('Search') }}</x-button>
        </x-input>
      </x-label>

    </form>

    <div class="w-full overflow-x-auto rounded-lg shadow-md ">
      <table class="w-full -no-wrap">
        <thead>
          <tr
            class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
            <th class="px-4 py-3">{{ __('Causer') }}</th>
            <th class="px-4 whitespacepy-3">{{ __('Log') }}</th>
            <th class="px-4 py-3">{{ __('Action') }}</th>
            <th class="px-4 py-3">{{ __('Time') }}</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          @foreach ($logs as $log)
            @php
              $properties = json_decode($log->properties, true);
            @endphp
            <tr class="text-gray-700 dark:text-gray-200">
              <td class="px-4 py-3 align-baseline">
                <div class="flex items-center text-sm">
                  <p class="font-semibold">
                    @if ($log->causer)
                      <a href='/admin/users/{{ $log->causer_id }}'>
                        {{ json_decode($log->causer)->name }}
                      </a>
                    @else
                      System
                    @endif
                  </p>
                </div>
              </td>
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
                        <svg class="w-5 h-5 transition-transform duration-200" aria-hidden="true" fill="currentColor"
                          viewBox="0 0 20 20" :class="{ 'rotate-180': description_open }">
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
          @endforeach
        </tbody>
      </table>
    </div>

    {!! $logs->links('pagination::tailwind') !!}
  </x-container>
@endsection
