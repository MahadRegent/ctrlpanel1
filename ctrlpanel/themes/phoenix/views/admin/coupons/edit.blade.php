@extends('layouts.main')

@section('content')
  <x-container title="Coupon Details">

    <x-card x-data="{ generate_random_codes: false, type: 'percentage' }">
      <form action="{{ route('admin.coupons.update', $coupon->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <x-validation-errors :errors="$errors" />

        <x-label title="Coupon Code">
          <x-input type="text" name="code" id="code" placeholder="SUMMER" required :value="$coupon->code" />
          @slot('text')
            {{ __('The coupon code to be registered.') }}
          @endslot
        </x-label>

        <x-label title="Coupon Type">
          <x-select x-on:change="(e) => type = e.target.value" required name="type" id="type">
            <option value="percentage" @if ($coupon->type == 'percentage') selected @endif>
              {{ __('Percentage') }}</option>
            <option value="amount" @if ($coupon->type == 'amount') selected @endif>{{ __('Amount') }}
            </option>
          </x-select>
          <x-slot name="text">
            {{ __('The way the coupon should discount') }}
          </x-slot>
        </x-label>

        <x-label title="Coupon Value">
          <x-input type="number" name="value" id="value" step="any" min="1" :value="$coupon->value"
            required />
          @slot('text')
            {{ __('The value that the coupon will represent.') }}
            <template x-if="type == 'percentage'">
              <span>({{ __('Percentage') }})</span>
            </template>
          @endslot
        </x-label>
        <x-label title="Max uses">
          <x-input type="number" name="max_uses" id="max_uses" min="1" required :value="$coupon->max_uses" />
          @slot('text')
            {{ __('The maximum number of times the coupon can be used.') }}
          @endslot
        </x-label>

        <x-label title="Expires At">
          <x-input value="{{ $expired_at ?? '' }}" name="expires_at" id="expires_at" placeholder="yyyy-mm-dd hh:mm:ss"
            type="text"></x-input>
          @slot('text')
            {{ __('The date when the coupon will expire (If no date is provided, the coupon never expires).') }}
          @endslot
        </x-label>

        <x-button type='submit'>{{ __('Submit') }}</x-button>
      </form>

    </x-card>

  </x-container>


  <script>
    document.addEventListener('DOMContentLoaded', (event) => {
      $('#expires_at').datetimepicker({
        // format: 'yyyy-mm-dd hh:mm:ss',
      });
    })
  </script>
@endsection
@section('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"
    integrity="sha512-AIOTidJAcHBH2G/oZv9viEGXRqDNmfdPVPYOYKGy3fti0xIplnlgMHUGfuNRzC6FkzIo0iIxgFnr9RikFxK+sw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('head')
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css"
    integrity="sha512-f0tzWhCwVFS3WeYaofoLWkTP62ObhewQ1EZn65oSYDZUg1+CyywGKkWzm8BxaJj5HGKI72PnMH9jYyIFz+GH7g=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
