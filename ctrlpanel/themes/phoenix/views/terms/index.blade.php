@extends('layouts.information')

@section('content')
  <x-container :title="$title">

    <x-card title="" class="">
      {!! $content !!}
    </x-card>
  </x-container>
@endsection
