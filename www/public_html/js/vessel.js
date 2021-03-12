jQuery(document).ready(function() {
  var groupColumn = 0;
   var table = $('.table').DataTable({
    searching: true, 
    paging: false, 
    info: false,
    responsive: true,
    autoWidth: false,
    lengthChange: false,
    colReorder:true,
    processing: true,
    language: {
      processing: '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>'
    },
    serverMethod: 'post',
    ajax: {
      url: document.location.origin + '/vessel/vesselSSR/',
     },
     columns: [
      { data: "container_number" },
      { data: "vessel_name" },
      { data: "location_city" },
      { data: "date_track" },
      { data: "status" },
      { data: "voyage" },
    ],
    drawCallback: function ( settings ) {
      var api = this.api();
      var rows = api.rows( {page:'current'} ).nodes();
      var last=null;
     
      api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
          if ( last !== group ) {
              $(rows).eq( i ).before(
                  '<tr class="group bg-primary"><td colspan="5"> Container - '+group+'</td></tr>'
              );

              last = group;
          }
      } );
  }
  }); 
  
  $('.a2b-marker-icon').on('click',function(){
    $(".vesselname").removeClass("d-none");
    setTimeout(function(){  
      $(".vesselname").addClass("d-none");
    },3000);
  });
});