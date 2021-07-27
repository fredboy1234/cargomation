
$(function () {

  getExchange('USD');

  //Initialize Select2 Elements
  $('.select2').select2();

  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
  });

  // Initialize Datatable
  var table = $('#forex').DataTable({
    searching: true,
    paging: true,
    info: false,
    responsive: true,
    autoWidth: false,
    pageLength: 30,
    lengthChange: false,
    colReorder: true,
    order: false,
    columns: [
      null,
      {orderable: false },
      {orderable: false },
      null,
      {orderable: false }
    ]
  });

  //on search data table
  $('#table_search').on('keyup', function () {
    table.search($(this).val()).draw();
  });

  $('#daterange').daterangepicker({
    "locale": {
      "applyLabel": "Search",
    }
  }, function (start, end, label) {
    $('input#start').val(start.format('DD MMM YYYY'));
    $('input#end').val(end.format('DD MMM YYYY'));
    console.log(start.format('DD MMM YYYY'));
    console.log(end.format('DD MMM YYYY'));
    table.draw();
  });

});

function getExchange(selectObject) {

  var amount = $('#amount').val();
  var code = $("#currency_code option:selected").val();

  $.ajax({
    url: document.location.origin + "/exchange/calculate/" + code,
    beforeSend: function () {
      $('#loader-wrapper').show();
    },
    success: function (res) {
      var obj = JSON.parse(res);
      $('#loader-wrapper').hide();
      $('#result').val(amount * obj[0].TTBuy);
    }
  });
}

/* Custom filtering function which will search data in column four between two values */
$.fn.dataTable.ext.search.push(
  function (settings, data, dataIndex) {
    var start = new Date($('#start').val());
    var end = new Date($('#end').val());
    var date = new Date(data[3])
    if ((isNaN(start) && isNaN(end)) ||
      (isNaN(start) && date <= end) ||
      (start <= date && isNaN(end)) ||
      (start <= date && date <= end)) {
      return true;
    }
    return false;
  }
);
