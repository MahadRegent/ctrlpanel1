@extends('layouts.main')

@section('content')
  <x-container title="Servers" btnText="Sync" :btnLink="route('admin.servers.sync')">
    @include('admin.servers.table')
  </x-container>
@endsection

@section('head')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
@endsection

@section('scripts')
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
@endsection
