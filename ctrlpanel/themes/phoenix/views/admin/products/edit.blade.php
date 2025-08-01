@extends('layouts.main')

@section('head')
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
@endsection

@section('content')
  <x-container title="Products">

    @if ($product->servers()->count() > 0)
      <x-alert title="Editing the resource options will not automatically update the servers on
        pterodactyls side!"
        type="danger">
      </x-alert>
    @endif

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
      @csrf
      @method('PATCH')

      <div class="grid gap-6 md:grid-cols-2">
        <x-card title="{{ __('Product Details') }}">

          <x-validation-errors :errors="$errors" />

          <x-checkbox title="Disabled" value="true" id="disabled" name="disabled" :checked="$product->disabled">
            {{ __('Will hide this option from being selected') }}
          </x-checkbox>

          <div class="grid gap-6 md:grid-cols-2">
            <x-label title="Name">
              <x-input value="{{ $product->name }}" id="name" name="name" type="text" />
            </x-label>
            <x-label title="Price in {{ $credits_display_name }}">
              <x-input value="{{ $product->price }}" id="price" name="price" step=".01" type="number" />
            </x-label>
            <x-label title="Memory">
              <x-input value="{{ $product->memory }}" id="memory" name="memory" type="number" />
            </x-label>
            <x-label title="CPU">
              <x-input value="{{ $product->cpu }}" id="cpu" name="cpu" type="number" />
            </x-label>
            <x-label title="Disk">
              <x-input value="{{ $product->disk }}" id="disk" name="disk" type="number" />
            </x-label>
            <x-label title="Swap">
              <x-input value="{{ $product->swap }}" id="swap" name="swap" type="number" />
            </x-label>
            <x-label title="Description" text="{{ __('This is what the users sees') }}">
              <x-textarea required id="description" name="description">{{ $product->description }}
              </x-textarea>
            </x-label>
            <x-label title="Billing Period">
              <x-select name='billing_period' autocomplete="off" required>
                <option value="hourly" {{ $product->billing_period == 'hourly' ? 'selected' : '' }}>
                  {{ __('Hourly') }}
                </option>
                <option value="daily" {{ $product->billing_period == 'daily' ? 'selected' : '' }}>
                  {{ __('Daily') }}
                </option>
                <option value="weekly" {{ $product->billing_period == 'weekly' ? 'selected' : '' }}>
                  {{ __('Weekly') }}
                </option>
                <option value="monthly" {{ $product->billing_period == 'monthly' ? 'selected' : '' }}>
                  {{ __('Monthly') }}
                </option>
                <option value="quarterly" {{ $product->billing_period == 'quarterly' ? 'selected' : '' }}>
                  {{ __('Quarterly') }}
                </option>
                <option value="half-annually" {{ $product->billing_period == 'half-anually' ? 'selected' : '' }}>
                  {{ __('Half Annually') }}
                </option>
                <option value="annually" {{ $product->billing_period == 'annually' ? 'selected' : '' }}>
                  {{ __('Annually') }}
                </option>
              </x-select>
              @slot('text')
                {{ __('Period when the user will be charged for the given price') }}
              @endslot
            </x-label>

            <x-label title="Minimum {{ $credits_display_name }}">
              <x-input value="{{ $product->minimum_credits }}" id="minimum_credits" name="minimum_credits"
                type="number" />
              @slot('text')
                {{ __('Setting to -1 will use the value from configuration.') }}
              @endslot
            </x-label>
            <x-label title="IO">
              <x-input value="{{ $product->io }}" id="io" name="io" type="number" />
            </x-label>
            <x-label title="Databases">
              <x-input value="{{ $product->databases }}" id="databases" name="databases" type="number" />
            </x-label>
            <x-label title="Backups">
              <x-input value="{{ $product->backups }}" id="backups" name="backups" type="number" />
            </x-label>
            <x-label title="Allocations">
              <x-input value="{{ $product->allocations }}" id="allocations" name="allocations" type="number" />
            </x-label>
            <x-label title="Server Limit">
              <x-input value="{{ $product->serverlimit }}" id="serverlimit" name="serverlimit" type="number" />
              @slot('text')
                {{ __('The maximum amount of Servers that can be created with this Product per User. 0 = unlimited') }}
              @endslot
            </x-label>
            <x-checkbox title="OOM Killer" value="true" id="oom_killer" name="oom_killer" :checked="$product->oom_killer">
              {{ __('Enable or Disable the OOM Killer for this Product.') }}
            </x-checkbox>
          </div>

        </x-card>

        <x-card title="{{ __('Product Linking') }}">
          <p class="text-sm">{{ __('Link your products to nodes and eggs to create dynamic pricing for each option') }}
          </p>
          <br>

          <x-label title="Select Nodes">
            <div>
              <select id="nodes" style="width:100%" class="nice-select wide @error('nodes') is-invalid @enderror"
                name="nodes[]" multiple="multiple" autocomplete="off">
                @foreach ($locations as $location)
                  <optgroup label="{{ $location->name }}">
                    @foreach ($location->nodes as $node)
                      <option @if (isset($product)) @if ($product->nodes->contains('id', $node->id)) selected @endif
                        @endif
                        value="{{ $node->id }}">{{ $node->name }}</option>
                    @endforeach
                  </optgroup>
                @endforeach
              </select>
            </div>

            @slot('text')
              {{ __('This product will only be available for these nodes') }}
            @endslot
          </x-label>


          <x-label title="Eggs">
            <div class="flex">
              <select id="eggs" style="width:100%" class="nice-select wide @error('eggs') is-invalid @enderror"
                name="eggs[]" multiple="multiple" autocomplete="off">
                @foreach ($nests as $nest)
                  <optgroup label="{{ $nest->name }}">
                    @foreach ($nest->eggs as $egg)
                      <option @if (isset($product) && $product->eggs->contains('id', $egg->id)) selected @endif value="{{ $egg->id }}">
                        {{ $egg->name }}</option>
                    @endforeach
                  </optgroup>
                @endforeach
              </select>
            </div>

            <div class="flex gap-2 mt-2">
              <x-button size="small" disabled type="button" id="select-all-eggs">{{ __('Select All') }}</x-button>
              <x-button size="small" disabled type="button"
                id="deselect-all-eggs">{{ __('Deselect All') }}</x-button>
            </div>
            <span class="text-xs italic text-gray-400 dark:text-gray-500">These buttons currently do not work, will be
              fixed in next version of phoenix
              theme.</span> <br>

            @slot('text')
              {{ __('This product will only be available for these eggs') }}
            @endslot
          </x-label>

          <p class="text-gray-500">
            {{ __('No Eggs or Nodes shown?') }} <a href="{{ route('admin.overview.sync') }}"
              class="text-primary-600 underline">{{ __('Sync now') }}</a>
          </p>

          <x-button type='submit' class="mt-4">{{ __('Submit') }}</x-button>

        </x-card>
      </div>
    </form>
  </x-container>

  <script>
    // TODO: Fix this
    // document.addEventListener('DOMContentLoaded', function() {
    //   let select = new NiceSelect(document.querySelector('#eggs'));
    //   document.getElementById('select-all-eggs').addEventListener('click', function() {
    //     console.log('select all');

    //     $('#eggs option').prop('selected', true);
    //     $('#eggs').trigger('change');
    //     select.update()
    //   });
    //   document.getElementById('deselect-all-eggs').addEventListener('click', function() {
    //     console.log('deselect all');
    //     $('#eggs option').prop('selected', false);
    //     $('#eggs').trigger('change');
    //     select.update()
    //   });
    // });
  </script>
@endsection
