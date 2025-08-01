@extends('layouts.main')
@section('head')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
@endsection

@section('scripts')
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
@endsection

@section('content')
  <x-container title="Products" btnLink="{{ route('admin.products.create') }}" btnText="Create">

    <div class="w-full overflow-hidden rounded-lg">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap" id="datatable">
          <thead>
            <tr
              class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">

              <th class="px-4 py-3">{{ __('Enabled') }}</th>
              <th class="px-4 py-3">{{ __('Name') }}</th>
              <th class="px-4 py-3">{{ __('Price') }}</th>
              <th class="px-4 py-3">{{ __('Billing Period') }}</th>
              <th class="px-4 py-3">{{ __('Memory') }}</th>
              <th class="px-4 py-3">{{ __('Cpu') }}</th>
              <th class="px-4 py-3">{{ __('Swap') }}</th>
              <th class="px-4 py-3">{{ __('Disk') }}</th>
              <th class="px-4 py-3">{{ __('Databases') }}</th>
              <th class="px-4 py-3">{{ __('Backups') }}</th>
              <th class="px-4 py-3">{{ __('OOM Killer') }}</th>
              <th class="px-4 py-3">{{ __('Nodes') }}</th>
              <th class="px-4 py-3">{{ __('Eggs') }}</th>
              <th class="px-4 py-3">{{ __('Servers') }}</th>
              <th class="px-4 py-3">{{ __('Server Limit') }}</th>
              <th class="px-4 py-3">{{ __('Created') }}</th>
              <th class="px-4 py-3 min-w-40">{{ __('Actions') }}</th>

            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
          </tbody>
        </table>
      </div>
      <x-pagination></x-pagination>

    </div>
  </x-container>

  <script>
    function submitResult() {
      return confirm("{{ __('Are you sure you wish to delete?') }}") !== false;
    }

    window.addEventListener("load", function() {
      window.table = $('#datatable').DataTable({
        order: [
          [2, "asc"]
        ],
        ajax: "{{ route('admin.products.datatable') }}",
        columns: [{
            data: "disabled"
          },
          {
            data: "name"
          },
          {
            data: "price"
          },
          {
            data: "billing_period"
          },
          {
            data: "memory"
          },
          {
            data: "cpu"
          },
          {
            data: "swap"
          },
          {
            data: "disk"
          },
          {
            data: "databases"
          },
          {
            data: "backups"
          },
          {
            data: "oom_killer"
          },
          {
            data: "nodes",
            sortable: false
          },
          {
            data: "eggs",
            sortable: false
          },
          {
            data: "servers",
          },
          {
            data: "serverlimit",
          },
          {
            data: "created_at"
          },
          {
            data: "actions",
            sortable: false
          }
        ]
      });
    });
  </script>
  <x-page_script></x-page_script>
@endsection
