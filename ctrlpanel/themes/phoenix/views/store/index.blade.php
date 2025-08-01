
@extends('layouts.main')
<?php use App\Models\ShopProduct; ?>

@section('content')
  <div class="container px-6 mx-auto grid max-w-2xl mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200 text-center">
      {{ __('Пополнение баланса') }}
    </h2>

    @if ($isStoreEnabled)
      <!-- Balance Top-up Form -->
      <x-card class="mb-6 shadow-lg" title="{{ __('Пополнение баланса') }}">
        <form action="{{ route('store.custom-topup') }}" method="POST" x-data="{ customAmount: '' }">
          @csrf
          <div class="flex flex-col space-y-4">
            <div>
              <x-label for="custom_amount" title="{{ __('Введите сумму для пополнения (в рублях)') }}" class="flex items-center" />
              <x-input
                id="custom_amount"
                name="custom_amount"
                type="number"
                step="1"
                min="1"
                max="10000"
                placeholder="100"
                x-model.number="customAmount"
                :value="old('custom_amount') ?? ''"
                class="!mt-1 w-full border-gray-300 dark:border-gray-600 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white rounded-lg pr-3"
                required
              />
            </div>
            @error('custom_amount')
              <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
            <div class="flex justify-center">
              <x-button
                type="submit"
                class="w-full sm:w-auto bg-primary-600 hover:bg-primary-700 active:bg-primary-800 text-white font-semibold rounded-lg px-6 py-2 transition-colors duration-200 ease-in-out"
                x-bind:disabled="!customAmount || customAmount < 1 || customAmount > 10000"
                x-bind:class="!customAmount || customAmount < 1 || customAmount > 10000 ? 'opacity-50 cursor-not-allowed' : ''"
              >
                {{ __('Создать платеж') }}
              </x-button>
            </div>
          </div>
          <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
            {{ __('Минимальная сумма: 1 ₽, Максимальная сумма: 10,000 ₽') }}
          </p>
        </form>
      </x-card>
    @else
      <x-alert title="{{ __('Ошибка') }}" type="danger">
        {{ __('Магазин не настроен корректно!') }}
      </x-alert>
    @endif

  </div>

  <script>
    const getUrlParameter = (param) => {
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      return urlParams.get(param);
    }
    document.addEventListener("DOMContentLoaded", function(event) {
      // Reset the custom amount input to ensure it starts empty
      const customAmountInput = document.querySelector('#custom_amount');
      if (customAmountInput) {
        customAmountInput.value = '';
        customAmountInput.dispatchEvent(new Event('input')); // Sync with x-model
      }

      // Handle voucher code if present
      const voucherCode = getUrlParameter('voucher');
      if (voucherCode) {
        const redeemModalElement = document.querySelector('[x-data*=data]');
        if (redeemModalElement && redeemModalElement._x_dataStack && redeemModalElement._x_dataStack[0].openRedeemModal) {
          redeemModalElement._x_dataStack[0].openRedeemModal();
          const redeemInput = document.querySelector('#redeemVoucherCode');
          if (redeemInput) {
            redeemInput.value = voucherCode;
          }
        }
      }
    });
  </script>

@endsection
