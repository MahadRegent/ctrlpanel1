@extends('layouts.main')

@section('content')
  <x-container title="Useful Links">

    <x-card>
      <form action="{{ route('admin.usefullinks.store') }}" method="POST">
        @csrf

        <x-validation-errors :errors="$errors" />

        <x-label title="Icon Class Name">
          <x-input value="{{ old('icon') }}" id="icon" name="icon" type="text" placeholder="fas fa-user"
            type="text" required></x-input>
          <x-slot name="text">
            {{ __('Icon will not be used, but it is still required.') }}
          </x-slot>
        </x-label>

        <x-label title="Title">
          <x-input value="{{ old('title') }}" id="title" name="title" type="text" type="text"
            required></x-input>
        </x-label>

        <x-label title="Link">
          <x-input value="{{ old('link') }}" id="link" name="link" type="text" type="text"
            required></x-input>
        </x-label>

        <x-label title="Description">
          <x-textarea id="description" name="description">{{ old('description') }}
          </x-textarea>
        </x-label>

        <x-label title="Position">
          <x-select name='position[]' id="position" autocomplete="off" required multiple class="nice-select wide">
            @foreach ($positions as $position)
              <option id="{{ $position->value }}" value="{{ $position->value }}">
                {{ __($position->value) }}
              </option>
            @endforeach
          </x-select>
        </x-label>

        <x-button type='submit'>{{ __('Submit') }}</x-button>
      </form>

    </x-card>

  </x-container>

  <script>
    tinymce.init({
      selector: 'textarea',
      skin: "Phoenix",
      promotion: false,
      // content_css: "Phoenix",
      skin_url: "/css/tinymce",
      body_class: 'tinymce-phoenix',
      branding: false,
      height: 500,
      plugins: ['image', 'link'],
    });

    window.addEventListener("load", (event) => {
      new NiceSelect(document.getElementById("position"));
    });
  </script>
@endsection
