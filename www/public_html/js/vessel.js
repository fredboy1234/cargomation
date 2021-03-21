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

  var mymap = L.map('mapid').setView([10.3130247, 123.9471531], 9);
  
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+mapToken, {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 9,
    id: 'mapbox/streets-v11',
    tileSize: 512,
    zoomOffset: -1,
    accessToken: mapToken
  }).addTo(mymap);
  
  var latlngs = [
    [-34.9284989, 138.3309724],
    [1.3139961, 103.7041626],
    [13.0474878, 80.0689246]
  ];
  var combination = [
    [[1.3139961, 103.7041626], [13.0474878, 80.0689246]],
  ]
  var latlngswithPopUp = [
    ['Vessel Name: SUEZ CANAL <br> Voyage: 0FD1UE1NL <br> Move: Empty to shipper',-34.9284989,138.3309724],
    ['Vessel Name: SUEZ CANAL <br> Voyage: 0FD1UE1NL <br> Move: Ready to be loaded ',1.3139961, 103.7041626],
    ['Vessel Name: SUEZ CANAL <br> Voyage: 0FD1UE1NL <br> Move: Discharged',13.0474878, 80.0689246]
  ];

  $.each(latlngswithPopUp,function(key,val){
    p = new L.Popup({ autoClose: false, closeOnClick: false })
                .setContent(val[0])
                .setLatLng([val[1], val[2]]);
    marker = new L.marker([val[1], val[2]])
    .bindPopup(p)
    .addTo(mymap).openPopup();
  });

  var firstlatlngs = [
    [-34.9284989, 138.3309724],
    [1.3139961, 103.7041626]
  ];
  var secondlatlngs = [
    [1.3139961, 103.7041626],
    [13.0474878, 80.0689246]
  ];

  var polyline = L.polyline(secondlatlngs
      , {
      color: 'red',
      weight: 5,
      opacity: 0.7,
      dashArray: '4,12,20,12',
      lineJoin: 'miter',
      lineCap: 'square',
    }).addTo(mymap);

    var polyline = L.polyline(firstlatlngs
      , {
      color: 'red',
      weight: 5,
      opacity: 0.7,
      dashArray: '4',
      lineJoin: 'miter',
      lineCap: 'square',
    }).addTo(mymap);
  
    mymap.fitBounds(polyline.getBounds().pad(Math.sqrt(2) / 3));

    // var myMovingMarker = L.Marker.movingMarker([[-34.9284989, 138.3309724],[1.3139961, 103.7041626]],
    //   [10000],{autostart: true}).addTo(mymap);

    // myMovingMarker.start();
    
    var animationMarker = L.Marker.movingMarker(
      latlngs,
      10000, {autostart: true});
    var greenIcon = L.divIcon({
      html: '<i class="fas fa-ship"></i>',
      iconSize: [40, 40],
      className: 'myDivIcon'
    });
    
    animationMarker.options.icon = greenIcon;
  
    mymap.addLayer(animationMarker );
  
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