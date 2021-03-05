
$(function () {
  // //Initialize Select2 Elements
  // $('.select2').select2();

  // //Initialize Select2 Elements
  // $('.select2bs4').select2({
  //   theme: 'bootstrap4'
  // });

});
$(document).on('ready', function () {
  getExchange('USD');
})

function getExchange(selectObject) {
  // var amount = $('#amount').val();
  // var code = $('#amount').val();
  // $("#myselect option:selected").text();

  // if ($.isNumeric(selectObject.value)) {
  //   amount = $('#amount').val();
  // } else {
  //   code = selectObject.value;
  // }

  var amount = $('#amount').val();
  var code = $("#currency_code option:selected").val();

  // if ($.isNumeric(selectObject.value)) {
  //   code = 
  // } else {
  //   code = selectObject.value;
  // }




  $.ajax({
    url: document.location.origin + "/exchange/calculate/" + code,
    success: function (res) {

      var obj = JSON.parse(res);
      console.log();
      $('#result').val(amount * obj[0].TTBuy);
    }
  });
}