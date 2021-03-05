
$(function () {

  getExchange('USD');

  //Initialize Select2 Elements
  $('.select2').select2();

  //Initialize Select2 Elements
  $('.select2bs4').select2({
    theme: 'bootstrap4'
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