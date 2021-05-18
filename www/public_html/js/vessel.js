jQuery(document).ready(function() {
    var groupColumn = 0;
    var indexCollection = [];
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
        // { data: "status" },
        { data: "voyage" },
        { data: "action" },
      ],
      drawCallback: function ( settings ) {
        var api = this.api();
        var rows = api.rows( {page:'current'} ).nodes();
        var last=null;
        var lastval = '';
        var dstn = '';
        var count = 1;
        var firstarry = [];
        api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
          var ndxData = $(rows).eq( i ).find('td').eq(2).find("p").text();
          var ndx = $(rows).eq( i ).find('td').eq(0).find("a").text();
         
          if(lastval == ndx || lastval ==''){ 
            firstarry.push(ndxData);
            indexCollection.push(firstarry);
          }
          
           lastval = ndx;
         
          if(rows.length == count){
            console.log(indexCollection[indexCollection.length-1]);
            // $.each(indexCollection[indexCollection.length-1],function(k,v){
            //   console.log(indexCollection);
            // });
          }
  
          if ( last !== group ) {
                var orgn = $(rows).eq( i ).find('td').eq(2).find("p").text();
                
                // $(rows).eq( i ).before(
                //     `<tr class="group collapse-tr">
                //       <td colspan="5"><a href="#">${$(group).text()}</a></td>
                //       <td><p class="d-inline-block col-md-2 offset-md-4">Origin: ${orgn}</p></td>
                //       <td><p class="dstn-${$(group).text()} d-inline-block col-md-2 ">Destination: ${ndxData}</p></td>
                //       <td class="sec"><i class="float-right fa fa-angle-down"></i></td>
                //     </tr>`
                // );
                last = group;
            }
            count++;
           
        } );
    }
    }); 
    
    $(document).on('click','.collapse-tr',function(){
      var cName = '.'+$(this).find('a').text();
      if($(this).find('.sec i').hasClass('fa-angle-down')){
        $(this).find('.sec i').removeClass('fa-angle-down');
        $(this).find('.sec i').addClass('fa-angle-up');
      }else{
        $(this).find('.sec i').removeClass('fa-angle-up');
        $(this).find('.sec i').addClass('fa-angle-down');
      }
      
      $(cName).parent().parent().toggle();
    });
  
    $('.a2b-marker-icon').on('click',function(){
      $(".vesselname").removeClass("d-none");
      setTimeout(function(){  
        $(".vesselname").addClass("d-none");
      },3000);
    });
  
    var mymap = L.map('mapid').setView([10.3130247, 123.9471531], 5);
    var polyArray = [];
    L.tileLayer('https://{s}.tile.jawg.io/jawg-matrix/{z}/{x}/{y}{r}.png?access-token=iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC', {
      attribution: '<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      minZoom: 0,
      maxZoom: 13,
      subdomains: 'abcd',
      accessToken: 'iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC'
    }).addTo(mymap);
    
    //set marker and popup
    $.each(searates.data.locations,function(key,val){
        polyArray.push([val.lat,val.lng]);
        L.marker([val.lat,val.lng]).bindPopup(
            '<div class="text-center">'
            +'<img class="img-thumbnail" src="https://www.myshiptracking.com/requests/getimage-small/271045135.jpg"><br><br>'
            +'<span>'+val.name+", "+val.country+'</span><br>'
            +'<a href="/vessel/tracking?'+vnum+'" class="btn btn-success text-white">Live Tracking</a>'
            +'</div>'
        ).addTo(mymap);
    });
    
    //set green line
    var polyline = L.polyline(polyArray
        , {
        color: '#00FF00',
        weight: 5,
        opacity: 0.7,
        dashArray: '4',
        lineJoin: 'miter',
        lineCap: 'square',
    }).addTo(mymap);

    mymap.fitBounds(polyline.getBounds());

    var animationMarker = L.Marker.movingMarker(
        polyArray,
        20000, 
        {autostart: true}
    );
    var greenIcon = L.divIcon({
        html: '<i class="fas fa-ship"></i>',
        iconSize: [40, 40],
        className: 'myDivIcon'
    });

    animationMarker.options.icon = greenIcon;
  
    mymap.addLayer(animationMarker );

    //set sidebar map
    var sidebar = L.control.sidebar('sidebar', {
        closeButton: true,
        position: 'left'
      });
      mymap.addControl(sidebar);
      sidebar.setContent($("#timeline").html());
      
      setTimeout(function () {
        sidebar.show();
      }, 1000);
  
      sidebar.on('show', function () {
        $("#refreshButton").addClass('d-none');
      });
  
      $("#refreshButton").on('click',function(){
        sidebar.show();
      });
  
      sidebar.on('hidden', function () {
        $("#refreshButton").removeClass('d-none');
      });
  
      mymap.on('click',function(){
        sidebar.hide();
      });

});  