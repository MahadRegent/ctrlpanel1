@extends('layouts.main')

@section('content')
  <x-container title="Preferences">
    <x-card title="">

      <form action="{{ route('preferences.update') }}" method="post">
        @csrf

        <x-label title="Language">
          <x-select required name="locale" id="locale" :disabled="!$localeSettings->clients_can_change" class="custom-select">
            @foreach (explode(',', $localeSettings->available) as $key)
              <option value="{{ $key }}" @if (session('locale') == $key) selected @endif>{{ ucfirst(__($key)) }}
              </option>
            @endforeach
          </x-select>

          @slot('text')
            @if (!$localeSettings->clients_can_change)
              {{ __('Changing the locale has been disabled by the System-Admins') }}
            @endif
          @endslot
        </x-label>

        <x-button type="submit">
          {{ __('Save Changes') }}
        </x-button>
      </form>
    </x-card>
  </x-container>

  <script>
    document.addEventListener('DOMContentLoaded', (event) => {
      $('.custom-select').select2();
    });
  </script>
@endsection


@section('head')
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
@endsection
