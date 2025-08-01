@extends('layouts.main')
@section('head')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
@endsection

@section('scripts')
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
@endsection


@section('content')
  <div class="container px-6 mx-auto grid">

    <div class="flex justify-between py-6">

      <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">
        {{ __('Store') }}
      </h2>
      <a class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-md focus:shadow-outline-purple active:bg-primary-600 hover:bg-primary-700 focus:shadow-outline-purple focus:outline-none"
        href="{{ route('admin.store.create') }}">{{ __('Create New') }}</a>
    </div>
    <div class="w-full overflow-hidden rounded-lg">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap m-0" id="datatable">
          <thead>
            <tr
              class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-600 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3">{{ __('Active') }}</th>
              <th class="px-4 py-3">{{ __('Type') }}</th>
              <th class="px-4 py-3">{{ __('Price') }}</th>
              <th class="px-4 py-3">{{ __('Display') }}</th>
              <th class="px-4 py-3">{{ __('Description') }}</th>
              <th class="px-4 py-3">{{ __('Created At') }}</th>
              <th class="px-4 py-3">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

          </tbody>
        </table>
      </div>
      <x-pagination></x-pagination>

    </div>
  </div>

  <script>
    function submitResult() {
      return confirm("{{ __('Are you sure you wish to delete?') }}") !== false;
    }

    window.addEventListener("load", function() {
      window.table = $('#datatable').DataTable({
        ajax: "{{ route('admin.store.datatable') }}",
        order: [
          [2, "desc"]
        ],
        columns: [{
            data: 'disabled'
          },
          {
            data: 'type'
          },
          {
            data: 'price'
          },
          {
            data: 'display',
            sortable: false
          },
          {
            data: 'description',
            sortable: false
          },
          {
            data: 'created_at'
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
