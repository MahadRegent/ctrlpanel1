@extends('layouts.main')

@section('content')
  <div class="container px-6 mx-auto grid">
    <div class="grid gap-6 mb-8 md:grid-cols-3">

      <div class="w-full overflow-hidden col-span-2">
        <h2 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
          {{ __('Ticket') }}
        </h2>
        <div class="w-full overflow-hidden rounded-lg ">
          <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap" id="datatable">
              <thead>
                <tr
                  class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">

                  <th class="px-4 py-3">{{ __('Title') }}</th>
                  <th class="px-4 py-3">{{ __('Category') }}</th>
                  <th class="px-4 py-3">{{ __('Priority') }}</th>
                  <th class="px-4 py-3">{{ __('Status') }}</th>
                  <th class="px-4 py-3">{{ __('Last Updated') }}</th>
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
      <div class="w-full overflow-hidden">
        <h2 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
          {{ __('Ticket Information') }}
        </h2>
        <div class="p-4 bg-white rounded-lg shadow-sm dark:bg-gray-800">

          <p class="mb-2">{!! $ticketsettings->information !!}</p>
          <a href="{{ route('ticket.new') }}"
            class="px-3 py-1 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-primary-600 border border-transparent rounded-md active:bg-primary-600 hover:bg-primary-700 focus:outline-none focus:shadow-outline-purple @cannot('user.ticket.write')) disabled @endcannot">Create
            Ticket</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    window.addEventListener("load", function() {
      window.table = $('#datatable').DataTable({
        ajax: "{{ route('ticket.datatable') }}",
        columns: [{
            data: 'title',
          },
          {
            data: 'category'
          },
          {
            data: 'priority'
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
