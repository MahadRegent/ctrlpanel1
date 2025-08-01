@extends('layouts.main')

@inject('vite', Vite::class)
@php
  $vite::useBuildDirectory('themes/phoenix');
@endphp

@section('content')
  <x-container title="Notify Users">

    <x-card>
      <form action="{{ route('admin.users.notifications.notify') }}" method="POST" x-data="{
          all: false,
      }">
        @csrf
        @method('POST')

        <x-validation-errors :errors="$errors" />

        <x-checkbox title="All Users" value="1" id="all" name="all" x-model="all">
        </x-checkbox>

        <div x-show="!all">
          <div id="users-form">
            <x-label title="Select Users">
              <select id="users" name="users[]" style="width:100%" multiple
                class="nice-select wide @error('users') is-invalid @enderror"></select>
            </x-label>
          </div>

          <div id="roles-form">
            <x-label title="Select roles">
              <select id="roles" style="width:100%" class="nice-select wide @error('roles') is-invalid @enderror"
                name="roles[]" multiple="multiple" autocomplete="off" x-on:input="console.log" onchange="hideUsersForm()">
                @foreach ($roles as $role)
                  <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
              </select>
            </x-label>
          </div>
        </div>

        <x-label title="Send Via">
          <x-checkbox title="Database" value="database" id="via[]" name="via[]"></x-checkbox>
          <x-checkbox title="Email" value="mail" id="via[]" name="via[]"></x-checkbox>
        </x-label>

        <x-label title="Title">
          <x-input value="{{ old('title') }}" id="title" name="title" type="text"></x-input>
        </x-label>

        <x-label title="Content">
          <x-textarea id="content" name="content" type="content">
            {{ old('content') }}</x-textarea>
        </x-label>

        <x-button type='submit'>{{ __('Submit') }}</x-button>
      </form>

    </x-card>

  </x-container>

  <script>
    function toggleClass(id, className) {
      document.getElementById(id).classList.toggle(className)
    }

    function hideUsersForm(event) {
      if (document.getElementById('roles').value.length > 0) {
        document.getElementById('users-form').style.display = 'none'
      } else {
        document.getElementById('users-form').style.display = 'block'
      }
    };


    function escapeHtml(str) {
      var div = document.createElement('div');
      div.appendChild(document.createTextNode(str));
      return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', (event) => {

      tinymce.init({
        selector: 'textarea',
        skin: "Phoenix",
        promotion: false,
        // content_css: "Phoenix",
        skin_url: "/themes/phoenix/tinymce",
        body_class: 'tinymce-phoenix',
        content_css: "/themes/phoenix/tinymce/content.min.css",
        branding: false,
        height: 500,
        plugins: ['image', 'link']
      });


      function initUserSelect(data) {
        $('#roles').select2({});
        $('#users').select2({
          placeholder: "Select users",
          dropdownAutoWidth: true,
          width: '100%',
          ajax: {
            url: '/admin/users.json',
            dataType: 'json',
            delay: 250,


            data: function(params) {
              return {
                filter: {
                  email: params.term
                },
                page: params.page,
              };
            },

            processResults: function(data, params) {
              return {
                results: data
              };
            },

            cache: true,
          },
          data: data,
          minimumInputLength: 2,

          escapeMarkup: function(markup) {
            return markup;
          },
          templateResult: function(data) {
            if (data.loading) return escapeHtml(data.text);
            return `
                    <span class="select2-item text-sm">
                      ${escapeHtml(data.name)} (${escapeHtml(data.email)})
                    </span>
                `;
          },

          templateSelection: function(data) {
            return data.text ? data.text : `
                    <span class="select2-item">
                      ${escapeHtml(data.name)} (${escapeHtml(data.email)})
                    </span>
                `;
          },
          formatSelection: function(object, container) {
            return `${escapeHtml(data.name)} (${escapeHtml(data.email)})`;
          }
        })
      }
      initUserSelect()
    })
  </script>
@endsection

@section('head')
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
  @vite('js/tinymce.js')
@endsection
