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
   
  //   L.tileLayer('https://{s}.tile.jawg.io/jawg-matrix/{z}/{x}/{y}{r}.png?access_token=iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC', {
  //   attribution: '<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
  //   maxZoom: 5,
  //   id: 'mapbox/streets-v11',
  //   tileSize: 512,
  //   zoomOffset: -1,
  //   accessToken: 'iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC'
  // }).addTo(mymap);
  L.tileLayer('https://{s}.tile.jawg.io/jawg-matrix/{z}/{x}/{y}{r}.png?access-token=iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC', {
    attribution: '<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    minZoom: 0,
    maxZoom: 13,
    subdomains: 'abcd',
    accessToken: 'iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC'
  }).addTo(mymap);
  
  var latlngCollection = [];
  var latlong = [];
  var lbefore = [];
  var lsbefore = [];
  var lafter = [];
  var lsafter = [];
  var exist = [];
  var existafter =[];
  
  if(datapolyline.before){
    $.each(datapolyline.before,function(key,val) {
      if(exist.includes(val.location_city)== false){
        exist.push(val.location_city.split(",")[0],);
      }
    });
    $.each(exist,function(okey,val){
      var city = val;
      $.ajax({
        async: false,
        url: "/vessel/scrape/",
        type: "POST",
        data: { "port_name": val},
        success: function( response ) { 
          if(response){  
            var oval = JSON.parse(response)[0];
            // var explodecountry = oval.port_name.split(", ");
            // explodecountry = explodecountry[oval.port_name.split(", ").length-1];
 
            var lat = parseFloat(oval.port_lat);
            var long = parseFloat(oval.port_long);
            latlong.push([lat,long]);  
            latlngCollection.push(latlong); 
            lbefore.push([lat,long]);
            lsbefore.push(latlong);

            // $.ajax({
            //   async: false,
            //   url: "/vessel/getFlag/",
            //   type: "POST",
            //   data: { "country": explodecountry},
            //   success:function(response){
            //     console.log(response);
            //   }
            // });

          }
        }
      }); 
    });
  }
  if(datapolyline.after){
    $.each(datapolyline.after,function(key,val) {
      if(existafter.includes(val.location_city)== false){
        existafter.push(val.location_city.split(",")[0],);
      }
    });
    $.each(existafter,function(okey,val){
      var city = val;
      $.ajax({
        async: false,
        url: "/vessel/scrape/",
        type: "POST",
        data: { "port_name": val},
        success: function( response ) { 
          if(response){  
            var oval = JSON.parse(response)[0];
            var lat = parseFloat(oval.port_lat);
            var long = parseFloat(oval.port_long);
            latlong.push([lat,long]);  
            latlngCollection.push(latlong); 
            lafter.push([lat,long]);
            lsafter.push(latlong);
          }
        }
      }); 
    });
  }
  
  setTimeout(function(){
    console.log(lsafter);
   console.log(lsbefore);
    console.log(latlngCollection);
    $.each(latlngCollection[0],function(key,val){
      
      // p = new L.Popup({ autoClose: false, closeOnClick: false })
      //             .setContent(val[0])
      //             .setLatLng([val[1], val[2]]);
      marker = new L.marker([val[0], val[1]])
      //.bindPopup(p)
      .addTo(mymap)//.openPopup();
    });
   if(lsafter[0]){
    var polyline = L.polyline(lsafter[0]
      , {
      color: 'red',
      weight: 5,
      opacity: 0.7,
      dashArray: '4',
      lineJoin: 'miter',
      lineCap: 'square',
    }).addTo(mymap);
   }
   if(lsbefore[0]){
    var polyline = L.polyline(lsbefore[0]
      , {
      color: '#00FF00',
      weight: 5,
      opacity: 0.7,
      dashArray: '4',
      lineJoin: 'miter',
      lineCap: 'square',
    }).addTo(mymap);
   }
   
  
    mymap.fitBounds(polyline.getBounds());
    
    var animationMarker = L.Marker.movingMarker(
      latlngCollection[0],
      10000, {autostart: true});
    var greenIcon = L.divIcon({
      html: '<i class="fas fa-ship"></i>',
      iconSize: [40, 40],
      className: 'myDivIcon'
    });
    console.log(animationMarker.getLatLng());
    animationMarker.options.icon = greenIcon;
  
    mymap.addLayer(animationMarker );

    
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

  },2000);
  
  
  // var latlngs = [
  //   [31.2322758, 121.4692071],
  //   [1.340863, 103.830391822121],
  //   [-34.9281805, 138.5999312],
  //   [1.340863, 103.830391822121],
  //   [-34.9281805, 138.5999312],
  //   [-34.9281805, 138.5999312]
  // ];
  
  var latlngswithPopUp = [
    ['Vessel Name: SUEZ CANAL <br> Voyage: 0FD1UE1NL <br> Move: Empty to shipper',-34.9284989,138.3309724],
    ['Vessel Name: SUEZ CANAL <br> Voyage: 0FD1UE1NL <br> Move: Ready to be loaded ',1.3139961, 103.7041626],
    ['Vessel Name: SUEZ CANAL <br> Voyage: 0FD1UE1NL <br> Move: Discharged',13.0474878, 80.0689246]
  ];

  // $.each(latlngs,function(key,val){
  //   // p = new L.Popup({ autoClose: false, closeOnClick: false })
  //   //             .setContent(val[0])
  //   //             .setLatLng([val[1], val[2]]);
  //   marker = new L.marker([val[0], val[1]])
  //   //.bindPopup(p)
  //   .addTo(mymap)//.openPopup();
  // });

  // var firstlatlngs = [
  //   [-34.9284989, 138.3309724],
  //   [1.3139961, 103.7041626]
  // ];
  // var secondlatlngs = [
  //   [1.3139961, 103.7041626],
  //   [13.0474878, 80.0689246]
  // ];

  // var polyline = L.polyline(secondlatlngs
  //     , {
  //     color: 'red',
  //     weight: 5,
  //     opacity: 0.7,
  //     dashArray: '4,12,20,12',
  //     lineJoin: 'miter',
  //     lineCap: 'square',
  //   }).addTo(mymap);

    // var polyline = L.polyline(latlngs
    //   , {
    //   color: 'red',
    //   weight: 5,
    //   opacity: 0.7,
    //   dashArray: '4',
    //   lineJoin: 'miter',
    //   lineCap: 'square',
    // }).addTo(mymap);
  
    // mymap.fitBounds(polyline.getBounds());

    // var myMovingMarker = L.Marker.movingMarker([[-34.9284989, 138.3309724],[1.3139961, 103.7041626]],
    //   [10000],{autostart: true}).addTo(mymap);

    // myMovingMarker.start();
    
    // var animationMarker = L.Marker.movingMarker(
    //   latlngs,
    //   10000, {autostart: true});
    // var greenIcon = L.divIcon({
    //   html: '<i class="fas fa-ship"></i>',
    //   iconSize: [40, 40],
    //   className: 'myDivIcon'
    // });
    
    // animationMarker.options.icon = greenIcon;
  
    // mymap.addLayer(animationMarker );
   
    // $('.lazy').Lazy({
    //   visibleOnly: true
    // });
  });


///
function animateMarekt(combination,mymap){

  var animationMarker = L.Marker.movingMarker(
    combination,
    10000, {autostart: true,loop: true});
  var greenIcon = L.divIcon({
    html: '<i class="fas fa-ship"></i>',
    iconSize: [40, 40],
    className: 'myDivIcon'
  });
  
  animationMarker.options.icon = greenIcon;

  mymap.addLayer(animationMarker );
}

// $.get( "https://restcountries.eu/rest/v2/all", function( data ) {
  
//   $.each(data,function(key,val){
//     $.ajax({
//       async: false,
//       url: "/vessel/country/",
//       type: "POST",
//       data: { 
//          "country_name": val.name,
//          "country_code2": val.alpha2Code,
//          "country_code3": val.alpha3Code,
//          "country_area": val.area,
//          "country_region": val.region,
//          "country_flag": val.flag,
//         },
//       success: function( response ) { 
//        console.log(response);
//       }
//     }); 
//   });
// });