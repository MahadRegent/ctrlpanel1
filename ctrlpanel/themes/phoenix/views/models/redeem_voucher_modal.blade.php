<script>
  function handleRedeemModal() {
    Swal.fire({
      title: "{{ __('Redeem voucher code') }}",
      showCancelButton: true,
      confirmButtonText: "{{ __('Redeem') }}",
      cancelButtonText: "{{ __('Cancel') }}",
      reverseButtons: true,
      input: "text",
      inputPlaceholder: "SUMMER",
      inputLabel: "Code",
      inputAttributes: {
        autocomplete: "off",
        name: "voucher_code"
      },
      showLoaderOnConfirm: true,
      preConfirm: async (code) => {
        try {
          const response = await fetch("{{ route('voucher.redeem') }}", {
            method: "POST",
            "headers": {
              "accept": "application/json",
              "content-type": "application/json",
            },
            body: JSON.stringify({
              "_token": "{{ csrf_token() }}",
              code: code
            })
          });
          if (!response.ok) {
            return Swal.showValidationMessage((await response.json()).message);
          }
          return response.json();
        } catch (error) {
          Swal.showValidationMessage(`
        Request failed: ${error}
      `);
        }
      },

      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.isConfirmed) {
        if (result.isConfirmed) {
          Swal.fire({
            title: result.value.success,
            icon: 'success',
            position: 'bottom-end',
            showConfirmButton: false,
            toast: true,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
              toast.addEventListener('mouseenter', Swal.stopTimer)
              toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
          });
        }

        return
      }
    });
  }
</script>
