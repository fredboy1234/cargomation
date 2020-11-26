$(document).ready(function(){

    $(".stat").on("click",function(){
        var doc_status = $(this).data("type");
        var doc_id = $(this).parent().data("id");
        $.ajax({
          type: "POST",
          url:"/admin/addDocumentStatus",
          ContentType: 'application/json',
          data:{"doc_status":doc_status,"doc_id":doc_id},
          success:function(response){
            console.log(response);
          }
        });
    });
});