@extends('layouts.main')

@section('content')
  <x-container title="Store" x-data="couponForm()">
    <form id="payment_form" action="{{ route('payment.pay') }}" method="POST">
      @csrf
      @method('post')



      <div class="grid lg:grid-cols-3 grid-cols-1 gap-6">
        @if (!$productIsFree)
          <x-card class="lg:col-span-2" containerClass="contents">

            <x-card class="!shadow-none !p-0" title="Payment Methods">
              <input type="hidden" name="product_id" value="{{ $product->id }}">
              <input type="hidden" name="payment_method" :value="payment_method" x-model="payment_method">

              @foreach ($paymentGateways as $gateway)
                <div class="row checkout-gateways @if (!$loop->last) mb-2 @endif">
                  <div class="flex justify-between items-center">
                    <label class="mb-4 text-lg text-gray-700 dark:text-gray-200" for="{{ $gateway->name }}">
                      <span class="mr-3">{{ $gateway->name }}</span>
                    </label>
                    <x-button class="" type="button" name="payment_method" id="{{ $gateway->name }}"
                      value="{{ $gateway->name }}" ::class="payment_method === '{{ $gateway->name }}' ? 'active' : ''"
                      x-on:click="payment_method = '{{ $gateway->name }}'; submitted = true;"
                      x-text="payment_method == '{{ $gateway->name }}' ? '{{ __('Selected') }}' : '{{ __('Select') }}'">
                      {{ __('Select') }}
                    </x-button>
                  </div>
                </div>
              @endforeach
            </x-card>

            <hr class="my-8 border-gray-300 dark:border-gray-600" />

            @if ($isCouponsEnabled)
              <x-card class="!shadow-none !p-0 !mb-0" title="Coupon">
                <div class="flex gap-4">
                  <x-input id="coupon_code" name="coupon_code" value="{{ old('coupon_code') }}" ::value="coupon_code"
                    placeholder="{{ __('Enter your coupon here...') }}" x-on:change.debounce="setCouponCode($event)"
                    x-model="coupon_code" type="text" class="!mt-0">
                  </x-input>
                  <x-button type="button" id="send_coupon_code" x-on:click="checkCoupon()" ::disabled="!coupon_code.length"
                    ::class="!coupon_code.length ? 'disabled' : ''" ::value="coupon_code">
                    {{ __('Submit') }}
                  </x-button>
                </div>
              </x-card>
            @endif
          </x-card>
        @endif
        <x-card class="">
          <x-card class="!shadow-none !p-0 !mb-0" title="Checkout Details">
            <ul class="list-group mb-3 leading-7">
              <li class="list-group-item">
                <div>
                  <h5 class="text-lg text-gray-700 dark:text-gray-200">{{ __('Product details') }}</h5>
                </div>
                <ul class="pl-0">
                  <li class="flex justify-between">
                    <span class="text-muted d-inline-block">{{ __('Type') }}</span>
                    <span
                      class="text-muted d-inline-block">{{ strtolower($product->type) == 'credits' ? $credits_display_name : $product->type }}</span>
                  </li>
                  <li class="flex justify-between">
                    <span class="text-muted d-inline-block">{{ __('Amount') }}</span>
                    <span class="text-muted d-inline-block">{{ $product->quantity }}</span>
                  </li>
                  <li class="flex justify-between">
                    <span class="text-muted d-inline-block">{{ __('Total Amount') }}</span>
                    <span class="text-muted d-inline-block">{{ $product->quantity }}</span>
                  </li>
                </ul>
              </li>
              <li class="list-group-item flex justify-between lh-condensed mt-4">
                <div>
                  <h6 class="text-lg text-gray-700 dark:text-gray-200">{{ __('Description') }}</h6>
                  <span class="text-muted">
                    {{ $product->description }}
                  </span>
                </div>
              </li>
              <li class="list-group-item mt-4">
                <div>
                  <h5 class="text-lg text-gray-700 dark:text-gray-200">{{ __('Pricing') }}</h5>
                </div>

                <ul class="pl-0">
                  <li class="flex justify-between">
                    <span class="text-muted d-inline-block">{{ __('Subtotal') }}</span>
                    <span class="text-muted d-inline-block">
                      {{ $product->formatToCurrency($product->price) }}</span>
                  </li>
                  <div class="flex justify-between">
                    <span class="text-muted d-inline-block">{{ __('Tax') }}
                      @if ($taxpercent > 0)
                        ({{ $taxpercent }}%):
                      @endif
                    </span>
                    <span class="text-muted d-inline-block">
                      + {{ $product->formatToCurrency($taxvalue) }}</span>
                  </div>
                  <div id="coupon_discount_details" class="flex justify-between" style="display: none !important;">
                    <span class="text-muted d-inline-block">
                      {{ __('Coupon Discount') }}
                    </span>
                    <span x-text="couponDiscountedValue" class="text-muted d-inline-block">

                    </span>
                  </div>
                  @if ($discountpercent && $discountvalue)
                    <div class="flex justify-between">
                      <span class="text-muted d-inline-block">{{ __('Partner Discount') }}
                        ({{ $discountpercent }}%)</span>
                      <span class="text-muted d-inline-block">
                        - {{ $product->formatToCurrency($discountvalue) }}
                      </span>
                    </div>
                  @endif
                  <hr class="my-4 border-gray-300 dark:border-gray-600" />
                  <div class="flex justify-between">
                    <span class="text-muted d-inline-block">{{ __('Total') }}</span>
                    <input id="total_price_input" type="hidden" x-model="totalPrice">
                    <span class="text-muted d-inline-block" x-text="formatToCurrency(totalPrice)">
                    </span>
                  </div>
                  <template x-if="payment_method">
                    <div class="flex justify-between">
                      <span class="text-muted d-inline-block">{{ __('Pay with') }}</span>
                      <span class="text-muted d-inline-block" x-text="payment_method"></span>
                    </div>
                  </template>
                </ul>
              </li>
            </ul>

            <x-button ::disabled="(!payment_method || !clicked || coupon_code) &&
            {{ !$productIsFree }}" id="submit_form_button" ::class="(!payment_method || !clicked || coupon_code) && {{ !$productIsFree }} ? 'disabled' : ''" ::x-text="coupon_code"
              x-on:click="clicked == true" class="w-full bg-green-600 hover:bg-green-700 active:bg-gray-700">
              @if ($productIsFree)
                {{ __('Get for free') }}
              @else
                {{ __('Submit Payment') }}
              @endif
            </x-button>
          </x-card>
        </x-card>
      </div>

    </form>
  </x-container>

  <script>
    function couponForm() {
      return {
        // Get the product id from the url
        productId: window.location.pathname.split('/').pop(),
        payment_method: '',
        coupon_code: '',
        submitted: false,
        totalPrice: {{ $total }},
        couponDiscountedValue: 0,


        setCouponCode(event) {
          this.coupon_code = event.target.value
        },

        async checkCoupon() {
          const response = await (fetch(
              "{{ route('admin.coupon.redeem') }}", {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                    'content')
                },
                body: JSON.stringify({
                  couponCode: this.coupon_code,
                  productId: this.productId
                })
              }
            )
            .then(response => response.json()).catch((error) => {
              Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ __('The coupon code you entered is invalid or cannot be applied to this product.') }}"
              })
            }))

          if (response.isValid && response.couponCode) {
            Swal.fire({
              icon: 'success',
              text: "{{ __('The coupon was successfully added to your purchase.') }}"

            })

            this.calcPriceWithCouponDiscount(response.couponValue, response
              .couponType)

            $('#submit_form_button').prop('disabled', false).removeClass(
              'disabled')
            $('#send_coupon_code').prop('disabled', true)
            $('#coupon_discount_details').prop('disabled', false).show()

          } else {
            Swal.fire({
              icon: 'error',
              title: 'Oops...',
              text: "{{ __('The coupon code you entered is invalid or cannot be applied to this product.') }}"
            })
          }
        },



        calcPriceWithCouponDiscount(couponValue, couponType) {
          let newTotalPrice = this.totalPrice


          console.log(couponType)
          if (couponType === 'percentage') {
            newTotalPrice = newTotalPrice - (newTotalPrice * couponValue / 100)
            this.couponDiscountedValue = "- " + couponValue + "%"
          } else if (couponType === 'amount') {

            newTotalPrice = newTotalPrice - couponValue
            this.couponDiscountedValue = "- " + this.formatToCurrency(couponValue)
          }

          // format totalPrice to currency
          this.totalPrice = this.formatToCurrency(newTotalPrice)
        },

        formatToCurrency(amount) {
          // get language for formatting currency - use en_US as product->formatToCurrency() uses it
          //const lang = "{{ app()->getLocale() }}"
          const lang = 'en-US'

          // format totalPrice to currency
          return amount.toLocaleString(lang, {
            style: 'currency',
            currency: "{{ $product->currency_code }}",
          })
        },

      }
    }
  </script>
@endsection
