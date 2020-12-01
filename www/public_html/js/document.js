$(document).ready(function() {

  $('.fas').hover(function() {
     $(this).toggleClass("fa-thumbs-up fa-thumbs-down");
  });

  $("#status").on("click",function() {
      var doc_status = $(this).data("doc_status");
      var doc_id = $(this).parent().data("doc_id");

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
          success:function(response) {
            alert('Document was set to ' + doc_status.charAt(0).toUpperCase() + doc_status.slice(1));
            // console.log(response);
            $('#status').children().toggleClass("fa-thumbs-up fa-thumbs-down");
            $('#status').data("doc_status", doc_status);
          }
        });
      }
  });
});