@extends('layouts.main')

@section('content')
  <x-container title="Partners" btnText="Create New" btnLink="{{ route('admin.partners.create') }}">

    <div class="w-full overflow-hidden rounded-lg">
      <div class="w-full overflow-x-auto">
        <table class="w-full whitespace-no-wrap" id="datatable">
          <thead>
            <tr
              class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-600 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
              <th class="px-4 py-3">{{ __('User') }}</th>
              <th class="px-4 py-3">{{ __('Partner Discount') }}</th>
              <th class="px-4 py-3">{{ __('Registered user discount') }}</th>
              <th class="px-4 py-3">{{ __('Referral system commission') }}</th>
              <th class="px-4 py-3">{{ __('Created') }}</th>
              <th class="px-4 py-3">{{ __('Actions') }}</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y-2 dark:divide-gray-700 dark:bg-gray-800">

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
        ajax: "{{ route('admin.partners.datatable') }}",
        columns: [{
            data: 'user'
          },
          {
            data: 'partner_discount'
          },
          {
            data: 'registered_user_discount'
          },
          {
            data: 'referral_system_commission'
          },
          {
            data: 'created_at'
          },
          {
            data: 'actions',
            sortable: false
          }
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
