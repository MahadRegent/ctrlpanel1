@extends('layouts.main')

@section('content')
  <x-container title="Server Settings">
    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">

      <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
        <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
          <!-- heroicons icon -->
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01">
            </path>
          </svg>
        </div>
        <div>
          <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
            {{ __('Server Name') }}
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">{{ $server->name }}
          </p>
        </div>
      </div>

      <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
        <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z">
            </path>
          </svg>
        </div>
        <div>
          <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
            CPU
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            @if ($server->product->cpu == 0)
              {{ __('Unlimited') }}
            @else
              {{ $server->product->cpu }} %
            @endif
          </p>
        </div>
      </div>

      <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
        <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
          </svg>
        </div>
        <div>
          <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Memory
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            @if ($server->product->memory == 0)
              {{ __('Unlimited') }}
            @else
              {{ $server->product->memory }}MB
            @endif
          </p>
        </div>
      </div>

      <div class="flex items-center p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
        <div class="p-3 mr-4 text-violet-500 bg-violet-100 rounded-full dark:text-violet-100 dark:bg-violet-500">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z">
            </path>
          </svg>
        </div>
        <div>
          <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
            {{ __('STORAGE') }}
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            @if ($server->product->disk == 0)
              {{ __('Unlimited') }}
            @else
              {{ $server->product->disk }}MB
            @endif
          </p>
        </div>
      </div>
    </div>

    <x-card title="Server Information">
      <div class="grid gap-6 mb-8 md:grid-cols-2">
        <div class="w-full overflow-x-auto rounded-lg shadow-sm ">
          <table class="w-full whitespace-no-wrap">
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3">
                  <div class="flex items-center text-sm">
                    <p class="font-semibold">
                      {{ __('Server ID') }}
                    </p>
                  </div>
                </td>

                <td class="px-4 py-3 text-sm">
                  {{ $server->id }}
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3">
                  <div class="flex items-center text-sm">
                    <p class="font-semibold">
                      {{ __('Pterodactyl ID') }}
                    </p>
                  </div>
                </td>

                <td class="px-4 py-3 text-sm">
                  {{ $server->identifier }}
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3">
                  <div class="flex items-center text-sm">
                    <p class="font-semibold">
                      {{ __('Hourly Price') }}
                    </p>
                  </div>
                </td>

                <td class="px-4 py-3 text-sm">
                  {{ number_format($server->product->getHourlyPrice(), 2, '.', '') }}
                  {{ $credits_display_name }}
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3">
                  <div class="flex items-center text-sm">
                    <p class="font-semibold">
                      {{ __('Monthly Price') }}
                    </p>
                  </div>
                </td>

                <td class="px-4 py-3 text-sm">
                  {{ $server->product->getHourlyPrice() * 24 * 30 }} {{ $credits_display_name }}
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
                  {{ $serverAttributes['relationships']['location']['attributes']['short'] }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="w-full overflow-x-auto rounded-lg shadow-sm ">
          <table class="w-full whitespace-no-wrap">
            <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3">
                  <div class="flex items-center text-sm">
                    <p class="font-semibold">
                      {{ __('Node') }}
                    </p>
                  </div>
                </td>

                <td class="px-4 py-3 text-sm">
                  {{ $serverAttributes['relationships']['node']['attributes']['name'] }}
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3">
                  <div class="flex items-center text-sm">
                    <p class="font-semibold">
                      {{ __('OOM Killer') }}
                    </p>
                  </div>
                </td>

                <td class="px-4 py-3 text-sm">
                  {{ $server->product->oom_killer ? __('enabled') : __('disabled') }}
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3">
                  <div class="flex items-center text-sm">
                    <p class="font-semibold">
                      {{ __('Backups') }}
                    </p>
                  </div>
                </td>

                <td class="px-4 py-3 text-sm">
                  {{ $server->product->backups }}
                </td>
              </tr>
              <tr class="text-gray-700 dark:text-gray-400">
                <td class="px-4 py-3">
                  <div class="flex items-center text-sm">
                    <p class="font-semibold">
                      {{ __('MySQL Database') }}
                    </p>
                  </div>
                </td>

                <td class="px-4 py-3 text-sm">
                  {{ $server->product->databases }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      @if ($server_enable_upgrade && Auth::user()->can('user.server.upgrade'))
        <x-modal title="{{ __('Upgrade/Downgrade Server') }}" btnTitle="{{ __('Upgrade / Downgrade') }}"
          btnClass=" mr-4 bg-primary-600 active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-purple">
          @slot('content')
            <strong>{{ __('Current Product') }}: </strong> {{ $server->product->name }}

            <form action="{{ route('servers.upgrade', ['server' => $server->id]) }}" method="POST" class="upgrade-form"
              id="upgrade-form">
              @csrf

              <x-select
                x-on:change="$el.value ? $refs.upgradeSubmit.disabled = false : $refs.upgradeSubmit.disabled = true"
                name="product_upgrade" id="product_upgrade">
                <option value="">{{ __('Select the product') }}</option>
                @foreach ($products as $product)
                  @if ($product->id != $server->product->id && $product->disabled == false)
                    <option value="{{ $product->id }}" @if ($product->doesNotFit) disabled @endif>
                      {{ $product->name }} [ {{ $credits_display_name }} {{ $product->price }} @if ($product->doesNotFit)
                        ] {{ __('Server can´t fit on this node') }}
                      @else
                        @if ($product->minimum_credits != -1)
                          /
                          {{ __('Required') }}: {{ $product->minimum_credits }} {{ $credits_display_name }}
                        @endif ]
                      @endif
                    </option>
                  @endif
                @endforeach
              </x-select>
              <br> <strong>{{ __('Caution') }}:</strong>
              {{ __('Upgrading/Downgrading your server will reset your billing cycle to now. Your overpayed Credits will be refunded. The price for the new billing cycle will be withdrawed') }}.
              <br>
              <br> {{ __('Server will be automatically restarted once upgraded') }}
            </form>
          @endslot

          @slot('buttons')
            <x-button x-ref="upgradeSubmit" disabled
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-purple"
              type="button" onclick="document.querySelector('#upgrade-form').submit()">
              {{ __('Change Product') }}
            </x-button>
          @endslot
        </x-modal>
      @endif
      <x-modal title="{{ __('Are you sure?') }}" btnTitle="Delete"
        btnClass="bg-red-600 active:bg-red-600 hover:bg-red-700 focus:shadow-outline-red">
        @slot('content')
          {{ __('This is an irreversible action, all files of this server will be removed.') }}
        @endslot
        @slot('buttons')
          <form method="post" action="{{ route('servers.destroy', ['server' => $server->id]) }}">
            @csrf
            @method('DELETE')
            <x-button
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red"
              type="submit">
              {{ __('Delete') }}
            </x-button>
          </form>
        @endslot
      </x-modal>
      <x-modal title="{{ __('Are you sure?') }}" btnTitle="Cancel Server"
        btnDisabled="{{ $server->suspended || $server->canceled }}"
        btnClass="ml-4 bg-yellow-600 active:bg-yellow-600 hover:bg-yellow-700 focus:shadow-outline-yellow disabled:cursor-not-allowed disabled:hover:bg-yellow-600 disabled:focus:shadow-none">
        @slot('content')
          {{ __('This will cancel your current server to the next billing period. It will get suspended when the current period runs out.') }}
        @endslot
        @slot('buttons')
          <form method="post" action="{{ route('servers.cancel', ['server' => $server->id]) }}">
            @csrf
            @method('PATCH')
            <x-button
              class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-yellow-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:shadow-outline-yellow"
              type="submit">
              {{ __('Yes, Cancel.') }}
            </x-button>
          </form>
        @endslot
      </x-modal>
    </x-card>


  </x-container>

  <script type="text/javascript">
    $(".upgrade-form").submit(function(e) {

      $(".upgrade-once").attr("disabled", true);
      return true;
    })
  </script>
@endsection
