@extends('layouts.main')

@section('head')
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
@endsection

@section('scripts')
  <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
@endsection

@section('content')
  <x-container title="Tickets" btnLink="{{ route('admin.ticket.category.index') }}" btnText="Add Category">
    <div class="w-full overflow-hidden rounded-lg ">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap" id="datatable">
          <thead>
            <tr
              class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">

              <th class="px-4 py-3">{{ __('Title') }}</th>
              <th class="px-4 py-3">{{ __('Category') }}</th>
              <th class="px-4 py-3">{{ __('User') }}</th>
              <th class="px-4 py-3">{{ __('Status') }}</th>
              <th class="px-4 py-3">{{ __('Last Updated') }}</th>
              <th class="px-4 py-3 min-w-20">{{ __('Actions') }}</th>

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
    window.addEventListener("load", function() {
      window.table = $('#datatable').DataTable({
        ajax: "{{ route('admin.ticket.datatable') }}",
        columns: [{
            data: 'title',
          },
          {
            data: 'category'
          },
          {
            data: 'user_id'
          },
          {
            data: 'status'
          },
          {
            data: 'updated_at',
            type: 'num',
            render: {
              _: 'display',
              sort: 'raw'
            }
          },
          {
            data: 'actions',
            sortable: false
          },
        ],
      })
    });
  </script>

  <x-page_script></x-page_script>
@endsection
