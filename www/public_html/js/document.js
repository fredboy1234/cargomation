$(document).ready(function() {

  // button can't be hover
  // $('button.kv-file-status').hover( function() {
  //   console.log($(this));
  //    $(this).children().toggleClass("fa-thumbs-up fa-thumbs-down");
  // });
  
  $('.kv-file-status').each(function() {
    if($(this).children().hasClass('approved')) {
      $(this).children().removeClass('fa-thumbs-down').addClass('fa-thumbs-up');
    }
  });

  $("button.kv-file-status").click( function() {
      var doc_status = $(this).data("doc_status");
      var doc_id = $(this).data("doc_id");

      if(doc_status === 'approved') 
        doc_status = 'pending';
      else 
        doc_status = 'approved';

      var msg = confirm("This will change the status of the document to " + doc_status.charAt(0).toUpperCase() + doc_status.slice(1) + " . Want to continue?");
      
      if(msg == true) {
        $.ajax({
          type: "POST",
          url:"/admin/addDocumentStatus",
          ContentType: 'application/json',
          data:{ "doc_status":doc_status, "doc_id":doc_id },
          success: function(response) {
            alert('Document was set to ' + doc_status.charAt(0).toUpperCase() + doc_status.slice(1));
            $('*[data-doc_id="' + doc_id + '"]').children().toggleClass("fa-thumbs-up fa-thumbs-down");
            $('*[data-doc_id="' + doc_id + '"]').children().toggleClass("approved pending");
            $('*[data-doc_id="' + doc_id + '"]').data("doc_status", doc_status);
          }
        });
      }
  });
});