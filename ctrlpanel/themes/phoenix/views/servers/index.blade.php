@extends('layouts.main')

@section('content')
  <div class="container px-6 mx-auto grid" x-data="{ server_id: null }">

    <div class="mb-4 sm:flex justify-between py-6">

      <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Servers') }}
      </h2>

      @php
        $user = Auth::user();
        if ($user->Servers->count() >= $user->server_limit) {
            $btnTooltip = 'Server limit reached!';
            $btnDisabled = true;
        } elseif (!$user->can('user.server.create')) {
            $btnTooltip = 'No Permission!';
            $btnDisabled = true;
        } else {
            $btnTooltip = null;
            $btnDisabled = false;
        }
      @endphp

      <div class="flex gap-4">
        <x-tooltip :content="$btnTooltip" class="border-none" pos="bottom">
          <button @disabled($btnDisabled) title="{{ $btnTooltip }}"
            class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-md disabled:opacity-50 disabled:cursor-not-allowed active:bg-primary-600 hover:bg-primary-700 focus:shadow-outline-purple focus:outline-none"
            onclick="window.location.href = '{{ route('servers.create') }}'">
            {{ __('Create Server') }}
          </button>
        </x-tooltip>
        @if (Auth::user()->Servers->count() > 0 && !empty($phpmyadmin_url))
          <a class="px-3 py-1 focus:ring focus:ring-gray-200 focus:ring-opacity-50 text-sm font-medium leading-5 dark:!text-white !text-gray-800 !no-underline transition-colors duration-150 bg-gray-600 border border-transparent rounded-md focus:shadow-outline-purple active:bg-gray-600 hover:bg-gray-600/50 focus:shadow-outline-purple focus:outline-none disabled:pointer-events-none disabled:cursor-not-allowed"
            href="{{ $phpmyadmin_url }}" target="_blank">
            {{ __('Database') }}
          </a>
        @endif
      </div>
    </div>

    <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">
      @forelse ($servers as $server)
        @if ($server->location && $server->node && $server->nest && $server->egg)
          <div class="min-w-0 p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800 max-w-[400px]">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-100">{{ $server->name }}
            </h2>
            <div class="w-full rounded-lg shadow-sm ">
              <table class="w-full whitespace-no-wrap">
                <thead>
                  <tr
                    class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                    <th class="px-4 py-3">{{ __('Resources') }}</th>
                    <th class="px-4 py-3">{{ __('Details') }}</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                  <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3">
                      <div class="flex items-center text-sm">
                        <p class="font-semibold">
                          {{ __('Status') }}
                        </p>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                      @if ($server->suspended)
                        <span
                          class="px-2 py-1 text-xs font-semibold leading-tight rounded-full text-red-700 bg-red-100 dark:bg-red-500/20 dark:text-red-500">
                          {{ __('Suspended') }}
                        </span>
                      @elseif($server->canceled)
                        <span
                          class="px-2 py-1 text-xs font-semibold leading-tight rounded-full text-yellow-700 bg-yellow-100 dark:bg-yellow-500/20 dark:text-yellow-500">
                          {{ __('Canceled') }}
                        </span>
                      @else
                        <span
                          class="px-2 py-1 text-xs font-semibold leading-tight rounded-full text-green-700 bg-green-100 dark:bg-green-500/20 dark:text-green-500">
                          {{ __('Active') }}
                        </span>
                      @endif
                    </td>
                  </tr>
                  <tr class="text-gray-700 dark:text-gray-400">
                    <td class="px-4 py-3">
                      <div class="flex items-center text-sm">
                        <p class="font-semibold">
                          {{ __('Location') }}
                        </p>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                      {{ $server->location }}
                    </td>
                  </tr>
                  <tr>
                    <td class="px-4 py-3">
                      <div class="flex items-center text-sm">
                        <p class="font-semibold">
                          {{ __('Node') }}
                        </p>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                      {{ $server->node }}
                    </td>
                  </tr>
                  <tr>
                    <td class="px-4 py-3">
                      <div class="flex items-center text-sm">
                        <p class="font-semibold">
                          {{ __('Software') }}
                        </p>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm">
                      {{ $server->nest }}, {{ $server->egg }}
                    </td>
                  </tr>
                  <tr>
                    <td class="px-4 py-3">
                      <div class="flex items-center text-sm">
                        <p class="font-semibold">
                          {{ __('Next Billing Cycle') }}
                        </p>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm flex items-center">
                      @if ($server->suspended || $server->canceled)
                        -
                      @else
                        @switch($server->product->billing_period)
                          @case('monthly')
                            {{ \Carbon\Carbon::parse($server->last_billed)->addMonth()->toDayDateTimeString() }}
                          @break

                          @case('weekly')
                            {{ \Carbon\Carbon::parse($server->last_billed)->addWeek()->toDayDateTimeString() }}
                          @break

                          @case('daily')
                            {{ \Carbon\Carbon::parse($server->last_billed)->addDay()->toDayDateTimeString() }}
                          @break

                          @case('hourly')
                            {{ \Carbon\Carbon::parse($server->last_billed)->addHour()->toDayDateTimeString() }}
                          @break

                          @case('quarterly')
                            {{ \Carbon\Carbon::parse($server->last_billed)->addMonths(3)->toDayDateTimeString() }}
                          @break

                          @case('half-annually')
                            {{ \Carbon\Carbon::parse($server->last_billed)->addMonths(6)->toDayDateTimeString() }}
                          @break

                          @case('annually')
                            {{ \Carbon\Carbon::parse($server->last_billed)->addYear()->toDayDateTimeString() }}
                          @break

                          @default
                            {{ __('Unknown') }}
                        @endswitch
                      @endif
                    </td>
                  </tr>
                  <tr>
                    <td class="px-4 py-3">
                      <div class="flex items-center text-sm">
                        <p class="font-semibold">
                          {{ __('Resource plan') }}
                        </p>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm flex items-center gap-2">
                      {{ $server->product->name }}

                      <x-tooltip html pos="top" class="border-none">
                        @slot('content')
                          <p class="text-left">
                            {{ __('CPU') }}: {{ $server->product->cpu / 100 }} {{ __('vCores') }} <br />
                            {{ __('RAM') }}: {{ $server->product->memory }} MB <br />
                            {{ __('Disk') }}: {{ $server->product->disk }} MB <br />
                            {{ __('Backups') }}: {{ $server->product->backups }} <br />
                            {{ __('MySQL Databases') }}: {{ $server->product->databases }} <br />
                            {{ __('Allocations') }}: {{ $server->product->allocations }} <br />
                            {{ __('OOM Killer') }}: {{ $server->product->oom_killer ? __('enabled') : __('disabled') }}
                            <br />
                            {{ __('Billing Period') }}: {{ $server->product->billing_period }}
                          </p>
                        @endslot
                        <x-icon icon="ph:info-bold" width="20" height="20" class="ml-1 mt-1" />
                      </x-tooltip>
                    </td>
                  </tr>
                  <tr>
                    <td class="px-4 py-3">
                      <div class="flex items-center text-sm">
                        <p class="font-semibold">
                          {{ __('Price') }}
                          <span class="font-normal dark:text-gray-400/75 text-gray-600">
                            ({{ $credits_display_name }})
                          </span>
                        </p>
                      </div>
                    </td>
                    <td class="px-4 py-3 text-sm flex gap-2 items-center">
                      {{ $server->product->price == round($server->product->price) ? round($server->product->price) : $server->product->price }}
                      <span class="font-normal dark:text-gray-400/75 text-gray-600">
                        @if ($server->product->billing_period == 'monthly')
                          {{ __('per Month') }}
                        @elseif($server->product->billing_period == 'half-annually')
                          {{ __('per 6 Months') }}
                        @elseif($server->product->billing_period == 'quarterly')
                          {{ __('per 3 Months') }}
                        @elseif($server->product->billing_period == 'annually')
                          {{ __('per Year') }}
                        @elseif($server->product->billing_period == 'weekly')
                          {{ __('per Week') }}
                        @elseif($server->product->billing_period == 'daily')
                          {{ __('per Day') }}
                        @elseif($server->product->billing_period == 'hourly')
                          {{ __('per Hour') }}
                        @endif
                      </span>

                      <x-tooltip html pos="top" class="border-none">
                        @slot('content')
                          {{ __('Your') . ' ' . $credits_display_name . ' ' . __('are reduced') . ' ' . $server->product->billing_period . '. ' }}
                          <br />
                          {{ __('This however calculates to ') . number_format($server->product->getMonthlyPrice(), 2, ',', '.') . ' ' . $credits_display_name . ' ' . __('per Month') }}
                        @endslot
                        <x-icon icon="ph:info-bold" width="20" height="20" class="ml-1 mt-1" />
                      </x-tooltip>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="mt-4 flex justify-evenly gap-4">
              <a href="{{ $pterodactyl_url }}/server/{{ $server->identifier }}" target="__blank"
                class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium leading-5 dark:text-white hover:text-white text-primary-600 transition-colors duration-150 border-primary-600 border-2 rounded-lg active:bg-primary-600 hover:bg-primary-600 text-center focus:outline-none focus:shadow-outline-purple">
                {{ __('Manage') }}
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"
                  xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25">
                  </path>
                </svg>
              </a>
              <a href="{{ route('servers.show', ['server' => $server->id]) }}"
                class="w-full px-4 py-3 text-sm font-medium leading-5 dark:text-white hover:text-white text-primary-600 transition-colors duration-150 border-primary-600 border-2 rounded-lg active:bg-primary-600 hover:bg-primary-600 text-center focus:outline-none focus:shadow-outline-purple">
                {{ __('Settings') }}
              </a>
            </div>
          </div>
        @endif
        @empty
          <div class="min-w-0 p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
            <h4 class="font-semibold text-gray-600 dark:text-gray-300">
              No Servers Found!
            </h4>
          </div>
        @endforelse
      </div>

    </div>
  @endsection
