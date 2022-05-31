function preloader(url) {

  var loader = '<div id="loader-wrapper" class="d-flex justify-content-center">' +
          '<div class="spinner-border" role="status">' +
          '<span class="sr-only">Loading...</span>' +
          '</div>' +
          '</div>';

  $("body").append(loader);

  // load the url and show modal on success
  $("#userList .modal-body").load(url,
      function (response, status, xhr) {
          if (xhr.status == 200) {
              $('#loader-wrapper').remove();
              $("#userList").modal("show");
          } else {
              alert("Error: " + xhr.status + ": " + xhr.statusText);
              $('#loader-wrapper').remove();
          }
      });
}
$(window).on("load", function() {
  
  // The Calender
  var preventer = [];
  var pointObject=[];
  var promises = [];

  setTimeout(function(){
    if (window.jQuery) {  
      $('#calendar').datetimepicker({
        format: 'L',
        inline: true
      });
    
      // Make the dashboard widgets sortable Using jquery UI
      $('.connectedSortable').sortable({
        placeholder: 'sort-highlight',
        connectWith: '.connectedSortable',
        handle: '.card-header, .nav-tabs',
        forcePlaceholderSize: true,
        zIndex: 999999
      });
      $('.connectedSortable .card-header').css('cursor', 'move');
    
      $(".datepicker-days table").on('click',"td[data-action='selectDay']",function(){
          var day = $(this).data('day');
          window.location.href = "/doctracker?calendar="+day;
      }); 
    }
  },3000);
});
$("a.action").click(function(event) {
  var url = "admin/" + this.dataset.action + "/" + this.dataset.id;
  switch (this.dataset.action) {
      case "show":
          preloader(url);
          break;
      case "edit":
          preloader(url);
          break;
      case "delete":
          Swal.fire({
              icon: 'warning',
              title: 'Are you sure you want to delete this account?',
              text: 'Account will be deleted and you won\'t be able to revert this!',
              showConfirmButton: true,
              showDenyButton: false,
              showCancelButton: true,
              confirmButtonText: `Delete`,
              denyButtonText: `Delete`,
          }).then((result) => {
              /* I use isDenied, coz of the color */
              if (result.isConfirmed) {
                  $.post( "contact/delete/" + this.dataset.id, function() {
                      Swal.fire('Account deleted!', 'This page will be refreshed.', 'success');
                  }).done(function() {
                      location.reload();
                      // alert( "second success" );
                  }).fail(function() {
                      // alert( "error" );
                  }).always(function() {
                      // alert( "finished" );
                  });
              }
              if (result.isDenied) {
                  resolve();
              } else if (result.isDismissed) {
                  Swal.fire('Account deletion was aborted!', 'Account "' + this.dataset.id + '" was not deleted.', 'info');
              }
          });
          break;
      default:
          break;
  }

});


