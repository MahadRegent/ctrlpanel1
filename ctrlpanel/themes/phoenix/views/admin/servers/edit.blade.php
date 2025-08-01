@extends('layouts.main')

@section('content')
  <x-container title="Edit Server">

    <x-alert title="Only edit these settings if you know exactly what you are doing" type="danger">
      {{ __('You usually do not need to change anything here') }}
    </x-alert>

    <x-card>
      <form action="{{ route('admin.servers.update', $server->id) }}" method="POST">
        @csrf
        @method('PATCH')

        <x-validation-errors :errors="$errors" />

        <x-label title="Server Identifier">
          <x-input value="{{ $server->identifier }}" id="identifier" name="identifier" type="text"></x-input>
        </x-label>

        <x-label title="Server owner">
          <x-select id="user_id" name="user_id" class="form-control">
            @foreach ($users as $user)
              <option value="{{ $user->id }}" @if ($user->id == $server->user_id) selected @endif>{{ $user->name }}
                ({{ $user->email }})
                ({{ $user->id }})
              </option>
            @endforeach
          </x-select>
          @slot('text')
            {{ __('Change the current server owner on CtrlPanel and pterodactyl.') }}
          @endslot
        </x-label>

        <x-button type='submit'>{{ __('Submit') }}</x-button>
      </form>

    </x-card>

  </x-container>
@endsection
