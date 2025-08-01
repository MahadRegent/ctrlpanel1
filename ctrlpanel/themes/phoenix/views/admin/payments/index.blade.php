@extends('layouts.main')
@section('head')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.css" />
@endsection

@section('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.24/datatables.min.js"></script>
@endsection


@section('content')
<x-container title="Payments" btnText="Download all Invoices" :btnLink="route('admin.invoices.downloadAllInvoices')"
  class="grid">

  <div class="relative overflow-x-auto rounded-lg" id="datatable_wrapper">
    <table class="whitespace-no-wrap" id="datatable">
      <thead>
        <tr
          class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-600 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">{{ __('ID') }}</th>
          <th class="px-4 py-3">{{ __('Type') }}</th>
          <th class="px-4 py-3">{{ __('Amount') }}</th>
          <th class="px-4 py-3">{{ __('Product Price') }}</th>
          <th class="px-4 py-3">{{ __('Tax Value') }}</th>
          <th class="px-4 py-3">{{ __('Tax Percentage') }}</th>
          <th class="px-4 py-3">{{ __('Total Price') }}</th>
          <th class="px-4 py-3">{{ __('User') }}</th>
          <th class="px-4 py-3">{{ __('Payment ID') }}</th>
          <th class="px-4 py-3">{{ __('Payment Method') }}</th>
          <th class="px-4 py-3">{{ __('Status') }}</th>
          <th class="px-4 py-3">{{ __('Created At') }}</th>
          <th class="px-4 py-3">{{ __('Actions') }}</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y-2 dark:divide-gray-700 dark:bg-gray-800">

      </tbody>
    </table>
  </div>
  <x-pagination></x-pagination>
</x-container>


<script>
  window.addEventListener("load", function() {
      window.table = $('#datatable').DataTable({
        ajax: "{{ route('admin.payments.datatable') }}",
        paging: true,
        order: [
          [9, "desc"]
        ],
        columns: [{
            data: 'id',
            name: 'payments.id'
          },
          {
            data: 'type'
          },
          {
            data: 'amount'
          },
          {
            data: 'price'
          },
          {
            data: 'tax_value'
          },
          {
            data: 'tax_percent'
          },
          {
            data: 'total_price'
          },
          {
            data: 'user'
          },
          {
            data: 'payment_id'
          },
          {
            data: 'payment_method'
          },
          {
            data: 'status'
          },
          {
            data: 'created_at',
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