@extends('layouts.main')
@inject('vite', Vite::class)
@php
  $vite::useBuildDirectory('themes/phoenix');
@endphp
@section('content')
  <x-container :title="isset($role) ? __('Edit role') : __('Create role')" raw_title>

    <x-card>
      <form action="{{ isset($role) ? route('admin.roles.update', $role->id) : route('admin.roles.store') }}"
        method="POST">
        @csrf
        @isset($role)
          @method('PATCH')
        @endisset

        <x-validation-errors :errors="$errors" />

        <x-label title="Name">
          <x-input required value="{{ isset($role) ? $role->name : null }}" id="name" name="name" type="text"
            required></x-input>
        </x-label>

        <x-label title="Badge color">
          <x-input class="coloris" :value="isset($role) ? $role->color : null" id="color" name="color" type="text" required></x-input>
        </x-label>

        <x-label title="Power">
          <x-input required value="{{ isset($role) ? $role->power : 10 }}" id="power" name="power" type="number"
            min="1" max="100" step="1" required></x-input>
        </x-label>

        <x-label title="Permissions">
          <x-select name="permissions[]" id="permissions" autocomplete="off" required multiple class="wide hidden">
            @foreach ($permissions as $permission)
              <option @if (isset($role) && $role->permissions->contains($permission)) selected @endif value="{{ $permission->id }}">
                {{ $permission->readable_name }}</option>
            @endforeach
          </x-select>
        </x-label>

        <x-button type='submit'>{{ __('Submit') }}</x-button>
      </form>

    </x-card>

  </x-container>
@endsection

@section('scripts')
  <script>
    window.addEventListener("load", (event) => {
      new NiceSelect(document.querySelector('#permissions'), {
        searchable: true
      });
    });
  </script>

  @vite('js/coloris.js')
@endsection
