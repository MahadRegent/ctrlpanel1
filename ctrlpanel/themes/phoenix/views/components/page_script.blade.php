<script>
  function configure_datatable(dt) {
    const prev_page_btn = $("#prev_page");
    const next_page_btn = $("#next_page");
    let table = dt || window.table || $('#datatable')
    let info = table.page.info();

    $('#table_footer_text').html('Showing page ' + (info.page + 1) + ' of ' + (info.pages + 1));
    table.on('page.dt', function() {
      info = table.page.info();
      $('#table_footer_text').html('Showing page ' + (info.page + 1) + ' of ' + (info.pages + 1));
    });
    prev_page_btn.on('click', (e) => {
      table.page(info.page <= 0 ? info.page : info.page - 1).draw(false);
    })
    next_page_btn.on('click', (e) => {
      table.page(info.page + 1).draw(false);
    })

    let btns = []
    for (let index = 0; index < info.pages; index++) {
      console.log("hello", index);
      btn =
        `<li><button onclick="window.table.page(${index}).draw(false);" class=" pagination_buttons px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">${index + 1}</button></li>`
      btns.push(btn)
    }
    btns.join()
    prev_page_btn.parent("li").after(btns)

    table.on('length.dt', () => {
      $(".pagination_buttons").remove()

      info = table.page.info();
      $('#table_footer_text').html('Showing page ' + (info.page + 1) + ' of ' + (info.pages + 1));

      let btns = []
      for (let index = 0; index < info.pages; index++) {
        btn =
          `<li><button onclick="window.table.page(${index}).draw(false);" class=" pagination_buttons px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">${index + 1}</button></li>`
        btns.push(btn)
      }
      btns.join()
      prev_page_btn.parent("li").after(btns)
    })

    table.on('preInit.dt', () => {
      const label = $("#datatable_length > label")
      label.contents().filter(function() {
        return this.nodeType == 3
      }).remove()
      label.addClass("block mb-4 text-sm text-gray-700 dark:text-gray-300")
      let span =
        `<span class="">{{ __('Show Per Page') }}</span>`
      $("#datatable_length > label > select").before(span)

      const wrapper = document.querySelector("#datatable_wrapper");
      wrapper.classList.remove("rounded-lg", "border-2", "border-b-0", "rounded-b-none", "border-white/15");
    });

    table.on('init.dt', convertIconsToIconify);
    table.on('draw.dt', convertIconsToIconify);
  }

  window.addEventListener("load", function() {
    configure_datatable()
  });
</script>
