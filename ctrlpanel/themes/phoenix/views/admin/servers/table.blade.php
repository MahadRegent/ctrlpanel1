<div class="w-full overflow-x-auto rounded-lg">
  <div class="w-full overflow-x-auto">
    <table class="w-full whitespace-no-wrap" id="datatable">
      <thead>
        <tr
          class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-600 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
          <th class="px-4 py-3">{{ __('Status') }}</th>
          <th class="px-4 py-3">{{ __('Name') }}</th>
          <th class="px-4 py-3">{{ __('User') }}</th>
          <th class="px-4 py-3">{{ __('Server ID') }}</th>
          <th class="px-4 py-3">{{ __('Product') }}</th>
          <th class="px-4 py-3">{{ __('Suspended At') }}</th>
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

<script>
  function submitResult() {
    return confirm("{{ __('Are you sure you wish to delete?') }}") !== false;
  }

  window.addEventListener("load", function() {
    window.table = $('#datatable').DataTable({
      ajax: "{{ route('admin.servers.datatable') }}{{ $filter ?? '' }}",
      order: [
        [6, "desc"]
      ],
      columns: [{
          data: 'status',
          name: 'servers.suspended',
          sortable: false
        },
        {
          data: 'name'
        },
        {
          data: 'user',
          name: 'user.name',
        },
        {
          data: 'identifier'
        },
        {
          data: 'resources',
          name: 'product.name',
          sortable: false
        },
        {
          data: 'suspended'
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
