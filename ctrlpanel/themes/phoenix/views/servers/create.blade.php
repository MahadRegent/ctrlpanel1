@extends('layouts.main')

@section('content')
  <div class="container px-6 mx-auto grid" x-data="serverApp()" id="server-creation-container">
    <div class="mb-4 flex justify-between py-6">

      <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Create Server') }}
      </h2>
    </div>

    <!-- FORM -->
    <form action="{{ route('servers.store') }}" method="POST" class="" x-on:submit="submitClicked = true"
      id="serverForm">
      @csrf
      @method('POST')
      <div class="px-4 py-3 pb-4 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <div class="card">

          <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">
            {{ __('Server Configuration') }}
          </h2>

          @if (!$server_creation_enabled)
            <x-alert title="The creation of new servers has been disabled for regular users" type="danger">
            </x-alert>
          @endif

          @if ($productCount === 0 || $nodeCount === 0 || count($nests) === 0 || count($eggs) === 0)
            <x-alert type="danger" title="Error!">
              <p class="">
                @if (Auth::user()->hasRole('Admin'))
                  {{ __('Make sure to link your products to nodes and eggs.') }} <br>
                  {{ __('There has to be at least 1 valid product for server creation') }}
                  <a href="{{ route('admin.overview.sync') }}"
                    class="m-2 text-primary-600 underline">{{ __('Sync now') }}</a>
                @endif
              </p>
              <ul>
                @if ($productCount === 0)
                  <li class="text-sm"> {{ __('No products available!') }}</li>
                @endif

                @if ($nodeCount === 0)
                  <li class="text-sm">{{ __('No nodes have been linked!') }}</li>
                @endif

                @if (count($nests) === 0)
                  <li class="text-sm">{{ __('No nests available!') }}</li>
                @endif

                @if (count($eggs) === 0)
                  <li class="text-sm">{{ __('No eggs have been linked!') }}</li>
                @endif
              </ul>
            </x-alert>
          @endif


          <div x-cloak x-show="loading" class="overlay dark">
            <i class="fas fa-2x fa-sync-alt"></i>
          </div>

          <div class="card-body">
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="list-group pl-3">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif

            <x-label title="Name">
              <x-input x-model="name" id="name" name="name" type="text" required
                placeholder="{{ __('Server Name') }}"></x-input>
            </x-label>

            <x-label title="Software / Games">
              <x-select x-on:change="setEggs();" name="nest" id="nest" x-model="selectedNest" required>
                <option selected disabled hidden value="null">
                  {{ count($nests) > 0 ? __('Please select software ...') : __('---') }}</option>
                @foreach ($nests as $nest)
                  <option value="{{ $nest->id }}">{{ $nest->name }}</option>
                @endforeach
              </x-select>
            </x-label>

            <x-label title="Specification">
              <x-select x-on:change="fetchLocations();" name="egg" id="egg" ::disabled="eggs.length == 0"
                x-model="selectedEgg" required>
                <option x-text="getEggInputText()" selected disabled hidden value="null">
                </option>
                <template x-for="egg in eggs" :key="egg.id">
                  <option x-text="egg.name" :value="egg.id"></option>
                </template>
              </x-select>
            </x-label>

            <x-label title="Location" class="!mb-0">
              @if ($location_description_enabled)
                @slot('text')
                  <span x-show="locationDescription != null" x-text="locationDescription"></span>
                @endslot
              @endif
              <x-select name="location" required id="location" x-model="selectedLocation" ::disabled="!fetchedLocations"
                @change="fetchProducts();">
                <option x-text="getLocationInputText()" disabled selected hidden value="null">
                </option>

                <template x-for="location in locations" :key="location.id">
                  <option x-text="location.name" :value="location.id">
                  </option>
                </template>
              </x-select>
            </x-label>

            <template x-if="selectedProduct != null && selectedProduct != '' && locations.length == 0 && !loading">
              <x-alert type='danger' class="!mt-4 !m-0"
                title="There seem to be no nodes available for this specification. Admins have been notified. Please try again later of contact us."></x-alert>
            </template>
          </div>
        </div>
      </div>

      <div x-cloak x-show="selectedLocation != null" x-data="{
          billingPeriodTranslations: {
              'monthly': '{{ __('per Month') }}',
              'half-annually': '{{ __('per 6 Months') }}',
              'quarterly': '{{ __('per 3 Months') }}',
              'annually': '{{ __('per Year') }}',
              'weekly': '{{ __('per Week') }}',
              'daily': '{{ __('per Day') }}',
              'hourly': '{{ __('per Hour') }}'
          }
      }">
        <input type="hidden" name="product" x-model="selectedProduct">
        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 my-6">
          {{ __('Products') }}
        </h2>
        <div class="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-3">


          <template x-for="product in products" :key="product.id">
            <div class="min-w-0 p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">
              <div class="flex justify-between items-center mb-4">

                <h2 class=" text-2xl font-semibold text-gray-700 dark:text-gray-100" x-text="product.name">
                </h2>
                <!-- Server Limit and Count -->
                <span class="text-muted"
                  x-text="product.serverlimit > 0
                   ? product.servers_count + ' / ' + product.serverlimit
                   : '{{ __('No limit') }}'">
                </span>
              </div>
              <div class="w-full overflow-x-auto rounded-lg shadow-sm ">
                <h3 class="font-semibold text-base text-gray-600 dark:text-gray-200">{{ __('Resource Data:') }}</h3>
                <table class="w-full whitespace-no-wrap">
                  <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    <tr class="text-gray-700 dark:text-gray-400">
                      <td class="pr-4 py-3">
                        <div class="flex items-center gap-2 text-sm">
                          <x-icon class="text-sm w-4" icon="fa-solid:microchip" />
                          <p class="font-semibold">
                            {{ __('CPU') }}
                          </p>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm" x-text="product.cpu + ' {{ __('vCores') }}'">
                      </td>
                    <tr>
                      <td class="pr-4 py-3">
                        <div class="flex items-center gap-2 text-sm">
                          <x-icon class="text-sm w-4" icon="fa-solid:memory" />
                          <p class="font-semibold">
                            {{ __('Memory') }}
                          </p>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm" x-text="product.memory + ' {{ __('MB') }}'">
                      </td>
                    </tr>
                    <tr>
                      <td class="pr-4 py-3">
                        <div class="flex items-center gap-2 text-sm">
                          <x-icon class="text-sm w-4" icon="fa-solid:hdd" />
                          <p class="font-semibold">
                            {{ __('Disk') }}
                          </p>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm" x-text="product.disk + ' {{ __('MB') }}'">
                      </td>
                    </tr>
                    <tr>
                      <td class="pr-4 py-3">
                        <div class="flex items-center gap-2 text-sm">
                          <x-icon class="text-sm w-4" icon="fa-solid:save" />
                          <p class="font-semibold">
                            {{ __('MySQL Databases') }}
                          </p>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm" x-text="product.databases">
                      </td>
                    </tr>
                    <tr>
                      <td class="pr-4 py-3">
                        <div class="flex items-center gap-2 text-sm">
                          <x-icon class="text-sm w-4" icon="fa-solid:database" />
                          <p class="font-semibold">
                            {{ __('Allocations') }}
                          </p>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm" x-text="product.allocations">
                      </td>
                    </tr>
                    <tr>
                      <td class="pr-4 py-3">
                        <div class="flex items-center gap-2 text-sm">
                          <x-icon class="text-sm w-4" icon="fa-solid:network-wired" />
                          <p class="font-semibold">
                            {{ __('Billing Period') }}
                          </p>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm" x-text="billingPeriodTranslations[product.billing_period]">
                      </td>
                    </tr>
                    <tr>
                      <td class="pr-4 py-3">
                        <div class="flex items-center gap-2 text-sm">
                          <x-icon class="text-sm w-4" icon="fa-solid:clock" />
                          <p class="font-semibold">
                            {{ __('Minimum') }} {{ $credits_display_name }}
                          </p>
                        </div>
                      </td>
                      <td class="px-4 py-3 text-sm"
                        x-text="product.minimum_credits == -1 ? {{ $min_credits_to_make_server }} : product.minimum_credits">
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="">
                <h3 class="font-semibold text-base text-gray-600 dark:text-gray-200">{{ __('Description:') }}</h3>
                <p class="text-gray-600 dark:text-gray-200" x-text="product.description"></p>
              </div>

              <div class="mt-4 border rounded-lg border-gray-300 dark:border-gray-600">
                <div class="p-2 flex justify-between">
                  <span class="mr-4 d-inline-block"
                    x-text="'{{ __('Price') }}' + ' (' + billingPeriodTranslations[product.billing_period] + ')'">
                  </span>
                  <span class="d-inline-block" x-text="product.price + ' {{ $credits_display_name }}'"></span>
                </div>
              </div>

              <div class="mt-4 flex gap-4">
                <button type="button"
                  :disabled="(product.minimum_credits > user.credits && product.price > user.credits) ||
                  product.doesNotFit == true ||
                      product.servers_count >= product.serverlimit && product.serverlimit != 0 ||
                      submitClicked"
                  :class="(product.minimum_credits > user.credits && product.price > user.credits) ||
                  product.doesNotFit == true ||
                      submitClicked ? 'disabled' : ''"
                  class="w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-lg active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-purple"
                  @click="setProduct(product.id);"
                  x-text="product.doesNotFit == true
                    ? '{{ __('Server cant fit on this Location') }}'
                    : (product.servers_count >= product.serverlimit && product.serverlimit != 0
                        ? '{{ __('Max. Servers with configuration reached') }}'
                        : (product.minimum_credits > user.credits && product.price > user.credits
                            ? '{{ __('Not enough') }} {{ $credits_display_name }}!'
                            : '{{ __('Create server') }}'))">
                </button>
                @if (env('APP_ENV') == 'local' || $store_enabled)
                  <template x-if="product.price > user.credits || product.minimum_credits > user.credits">
                    <a href="{{ route('store.index') }}"
                      class="w-full flex items-center justify-center px-4 py-2 focus:ring focus:ring-yellow-200 focus:ring-opacity-50 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-yellow-600 border border-transparent rounded-md focus:shadow-outline-purple active:bg-yellow-600 hover:bg-yellow-700 focus:shadow-outline-purple focus:outline-none">
                      {{ __('Buy more') }} {{ $credits_display_name }}
                    </a>
                  </template>
                @endif
              </div>
            </div>
          </template>
        </div>
      </div>

      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <input type="hidden" name="product" id="product" x-model="selectedProduct">
      <input type="hidden" name="egg_variables" id="egg_variables">
    </form>
  </div>

  <script>
    const getUrlParameter = (param) => {
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      return urlParams.get(param);
    }

    document.addEventListener("DOMContentLoaded", function(event) {
      const alpine_data = document.querySelector('#server-creation-container')._x_dataStack[0];
      const software = getUrlParameter('software');
      const egg = getUrlParameter('specification');

      if (software) {
        document.querySelector('#nest').value = software;
        alpine_data.selectedNest = software;
        alpine_data.setEggs();
      }

      if (egg && alpine_data.eggs.length > 0) {
        document.querySelector('#egg').value = egg;
        alpine_data.selectedEgg = egg;

        alpine_data.fetchLocations();
      }
    });

    function serverApp() {
      return {
        //loading
        loading: false,
        fetchedLocations: false,
        fetchedProducts: false,

        //input fields
        name: null,
        selectedNest: null,
        selectedEgg: null,
        selectedLocation: null,
        selectedProduct: null,
        locationDescription: null,

        //selected objects based on input
        selectedNestObject: {},
        selectedEggObject: {},
        selectedLocationObject: {},
        selectedProductObject: {},

        //values
        user: {!! $user !!},
        nests: {!! $nests !!},
        eggsSave: {!! $eggs !!}, //store back-end eggs
        eggs: [],
        locations: [],
        products: [],

        submitClicked: false,


        /**
         * @description set available eggs based on the selected nest
         * @note called whenever a nest is selected
         * @see selectedNest
         */
        async setEggs() {
          this.fetchedLocations = false;
          this.fetchedProducts = false;
          this.locations = [];
          this.products = [];
          this.selectedEgg = 'null';
          this.selectedLocation = 'null';
          this.selectedProduct = null;
          this.locationDescription = null;

          this.eggs = this.eggsSave.filter(egg => egg.nest_id == this.selectedNest)

          //automatically select the first entry if there is only 1
          if (this.eggs.length === 1) {
            this.selectedEgg = this.eggs[0].id;
            await this.fetchLocations();
            return;
          }

          this.updateSelectedObjects()
        },

        setProduct(productId) {
          if (!productId) return

          this.selectedProduct = productId;
          this.updateSelectedObjects();

          let hasEmptyRequiredVariables = this.hasEmptyRequiredVariables(this.selectedEggObject.environment);

          if (hasEmptyRequiredVariables.length > 0) {
            this.dispatchModal(hasEmptyRequiredVariables);
          } else {
            document.getElementById('product').value = productId;
            document.getElementById('serverForm').submit();
          }

        },

        /**
         * @description fetch all available locations based on the selected egg
         * @note called whenever a server configuration is selected
         * @see selectedEg
         */
        async fetchLocations() {
          this.loading = true;
          this.fetchedLocations = false;
          this.fetchedProducts = false;
          this.locations = [];
          this.products = [];
          this.selectedLocation = 'null';
          this.selectedProduct = 'null';

          let response = await fetch(`{{ route('products.locations.egg') }}/${this.selectedEgg}`).then(
              data => data.json())
            .catch(console.error)

          this.fetchedLocations = true;
          this.locations = response

          //automatically select the first entry if there is only 1
          if (this.locations.length === 1 && this.locations[0]?.nodes?.length === 1) {
            this.selectedLocation = this.locations[0]?.id;
            await this.fetchProducts();
            return;
          }

          this.loading = false;
          this.updateSelectedObjects()
        },

        /**
         * @description fetch all available products based on the selected location
         * @note called whenever a location is selected
         * @see selectedLocation
         */
        async fetchProducts() {
          this.loading = true;
          this.fetchedProducts = false;
          this.products = [];
          this.selectedProduct = null;

          let response = await fetch(
              `{{ route('products.products.location') }}/${this.selectedEgg}/${this.selectedLocation}`).then(
              data => data.json())
            .catch(console.error)

          this.fetchedProducts = true;
          // TODO: Sortable by user chosen property (cpu, ram, disk...)
          this.products = response.sort((p1, p2) => parseInt(p1.price, 10) > parseInt(p2.price, 10) &&
            1 || -1)

          this.products.forEach(product => {
            //divide cpu by 100 for each product
            product.cpu = product.cpu / 100;

            //format price to have no decimals if it is a whole number
            if (product.price % 1 === 0) {
              product.price = Math.round(product.price);
            }
          })

          this.locationDescription = this.locations.find(location => location.id == this.selectedLocation)
            .description ?? null;

          this.loading = false;
          this.updateSelectedObjects()
        },

        /**
         * @description map selected id's to selected objects
         * @note being used in the server info box
         */
        updateSelectedObjects() {
          this.selectedNestObject = this.nests.find(nest => nest.id == this.selectedNest) ?? {}
          this.selectedEggObject = this.eggs.find(egg => egg.id == this.selectedEgg) ?? {}

          this.selectedLocationObject = {};
          this.locations.forEach(location => {
            if (!this.selectedLocationObject?.id) {
              this.selectedLocationObject = location.nodes.find(node => node.id == this.selectedLocation) ?? {};
            }
          })

          this.selectedProductObject = this.products.find(product => product.id == this.selectedProduct) ?? {}
          console.log(this.selectedProduct, this.selectedProductObject, this.products)
        },

        /**
         * @description check if all options are selected
         * @return {boolean}
         */
        isFormValid() {
          if (Object.keys(this.selectedNestObject).length === 0) return false;
          if (Object.keys(this.selectedEggObject).length === 0) return false;
          if (Object.keys(this.selectedLocationObject).length === 0) return false;
          if (Object.keys(this.selectedProductObject).length === 0) return false;
          return !!this.name;
        },

        hasEmptyRequiredVariables(environment) {
          if (!environment) return [];

          return environment.filter((variable) => {
            const hasRequiredRule = variable.rules?.includes("required");
            const isDefaultNull = !variable.default_value;

            return hasRequiredRule && isDefaultNull;
          });
        },


        getLocationInputText() {
          if (this.fetchedLocations) {
            if (this.locations.length > 0) {
              return '{{ __('Please select a location ...') }}';
            }
            return '{{ __('No locations found matching current configuration') }}'
          }
          return '{{ __('---') }}';
        },

        getProductInputText() {
          if (this.fetchedProducts) {
            if (this.products.length > 0) {
              return '{{ __('Please select a resource ...') }}';
            }
            return '{{ __('No resources found matching current configuration') }}'
          }
          return '{{ __('---') }}';
        },

        getEggInputText() {
          if (this.selectedNest) {
            return '{{ __('Please select a configuration ...') }}';
          }
          return '{{ __('---') }}';
        },

        getProductOptionText(product) {
          let text = product.name + ' (' + product.description + ')';

          if (product.minimum_credits > this.user.credits) {
            return '{{ __('Not enough credits!') }} | ' + text;
          }

          return text;
        },

        dispatchModal(variables) {
          Swal.fire({
            title: '{{ __('Required Variables') }}',
            html: variables.map((variable) => {
              const isSelect = variable.rules.includes("in:");

              const selectOptions = isSelect && variable.rules.match(/in:([^|]+)/)[1].split(',');

              console.log(selectOptions);

              return `
                <x-label title="${variable.name}" html>

                  ${isSelect ? `
                                <x-select id="${variable.env_variable}" name="${variable.env_variable}" required>
                                  ${selectOptions.map(value => `<option value="${value}">${value}</option>`).join('')}
                                </x-select>
                                ` : `
                                <x-input id="${variable.env_variable}" name="${variable.env_variable}" type="text" required></x-input>
                              `}

                  @slot('text')
                    <p id="${variable.env_variable}-error" class="text-red-500"></p>
                    <p>${variable.description}</p>
                  @endslot
                </x-label>
            `;
            }).join(''),
            confirmButtonText: '{{ __('Submit') }}',
            showCancelButton: true,
            cancelButtonText: '{{ __('Cancel') }}',
            showLoaderOnConfirm: true,
            preConfirm: async () => {
              const filledVariables = variables.map(variable => {
                const value = document.getElementById(variable.env_variable).value;
                return {
                  ...variable,
                  filled_value: value
                };
              });

              const response = await fetch('{{ route('servers.validateDeploymentVariables') }}', {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                  variables: filledVariables
                })
              })

              if (!response.ok) {
                const errorData = await response.json();

                variables.forEach(variable => {
                  const errorContainer = document.getElementById(`${variable.env_variable}-error`);
                  if (errorContainer) {
                    errorContainer.innerHTML = '';
                  }
                });

                if (errorData.errors) {
                  Object.entries(errorData.errors).forEach(([key, messages]) => {
                    const errorContainer = document.getElementById(`${key}-error`);
                    if (errorContainer) {
                      errorContainer.innerHTML = messages.map(message => `
                        <small class="text-danger">${message}</small>
                    `).join('');
                    }
                  });
                }

                return false;
              }

              return response.json();
            },
            didOpen: () => {
              $('[data-toggle="tooltip"]').tooltip();
            },
          }).then((result) => {
            if (result.isConfirmed && result.value.success) {
              document.getElementById('egg_variables').value = JSON.stringify(result.value.variables);
              document.getElementById('serverForm').submit();
            }
          });
        }
      }
    }
  </script>
@endsection
