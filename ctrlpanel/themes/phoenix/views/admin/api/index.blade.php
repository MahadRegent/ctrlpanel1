@extends('layouts.main')

@section('content')
  <x-container title="Application API" btnText="Create New" :btnLink="route('admin.api.create')">

    <div class="w-full overflow-hidden rounded-lg">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap m-0" id="datatable">
          <thead>
            <tr
              class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-600 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3">{{ __('Token') }}</th>
              <th class="px-4 py-3">{{ __('Memo') }}</th>
              <th class="px-4 py-3">{{ __('Last Used') }}</th>
              <th class="px-4 py-3">{{ __('Actions') }}</th>
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
        ajax: "{{ route('admin.api.datatable') }}",
        order: [
          [2, "desc"]
        ],
        columns: [{
            data: 'token'
          },
          {
            data: 'memo'
          },
          {
            data: 'last_used'
          },
          {
            data: 'actions',
            sortable: false
          },
        ],
      });
    });
  </script>
  <x-page_script></x-page_script>
@endsection

@section('head')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
@endsection

@section('scripts')
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
@endsection
