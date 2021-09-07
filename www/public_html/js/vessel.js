jQuery(document).ready(function() {
    var groupColumn = 0;
    var indexCollection = [];

    $('.table thead th').each( function () {
      var title = $(this).text();
      //console.log(title);
      if($.trim(title) !== 'Action'){
        $(this).append( '<input type="text" placeholder="Search '+title+'" />' );
      }
      
    });

     var table = $('.table').DataTable({
      searching: true, 
      paging: false, 
      info: false,
      responsive: false,
      autoWidth: false,
      lengthChange: false,
      //colReorder:true,
      processing: true,
      language: {
        processing: '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>'
      },
      initComplete: function () {
        this.api().columns().every( function () {
            var that = this;
            $( 'input', this.header() ).on( 'keyup change clear input', function () {
              //console.log(this.value );
                if ( that.search() !== this.value ) {
                 
                    that
                        .search( this.value )
                        .draw();
                }
            });
        } );
    },
      serverMethod: 'post',
      ajax: {
        url: document.location.origin + '/vessel/vesselSSR/',
       },
       columns: [
        { data: "container_number" },
        { data: "shipment_num"},
        //{// data: "housebill"},
        { data: "masterbill"},
        { data: "voyage" },

        { data: "date_track" },
        { data: "vessel_name" },
        { data: "location_city" },
        { data: "onestop" },
        // { data: "status" },
            
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
          
          $(rows).eq( i ).addClass($(rows).eq( i ).find('td').find("p").attr('class'));
          var shipid =  $(rows).eq( i ).find('td').eq(0).find("p").attr('id');
          if ( last !== group ) {
                var orgn = $(rows).eq( i ).find('td').eq(2).find("p").text();
                var shipmnt = $(rows).eq( i ).find('td').eq(1).text();
            
                //$(rows).eq( i ).find('td').eq(2).append("<span></span>");
                var seDecode = {}//JSON.parse(searates[i].sea_json);
                var events = [];
                var htmlstat = '';
                var currentDay = new Date();
                var unID = '';
                var htmlOnestop = '';
                var tTable ='';
                var catcher = 0;
                var bg = ['bg-primary','bg-success','bg-warning','bg-success'];
                try{
                  
                  seDecode = JSON.parse(searates[i].sea_json);
                  if(typeof(seDecode.data.container) != null && typeof seDecode.data.container != "undefined"){
                    if(typeof(seDecode.data.container.events) != "undefined" && typeof(seDecode.data.container.events) != null){
                      events = seDecode.data.container.events;
                    }
                    
                  }
                  $.each(events,function(okey,oval){
                    var status = oval.status;
                    var location = '';
                    var locindex = oval.location;
                    
                    var containerDay = new Date(oval.date);
                    var same = currentDay.getTime() === containerDay.getTime();
                    var notSame = currentDay.getTime() !== containerDay.getTime();
                    
  
                    $.each(seDecode.data.locations,function(ok,ov){
                      
                      if(ov.id == locindex){
                        location = `${ov.name}`;
                      }
                    });
  
                    htmlstat +=`
                        <span>Location : ${location}</span><br>
                        <span> Date : ${oval.date}</span><br> 
                        <span> Status : ${statsCode[status]}</span><br>  
                        <hr>  
                    `;
                  });
                  $(rows).eq( i ).attr("data-details",searates[i].trans_id);
                  $(rows).eq( i ).after(
                      `<tr class="d-none ${searates[i].trans_id} ">
                        <td>${htmlstat}</td>
                      </tr>`
                  );
                }catch(err){
                  seDecode = searates;
                  events = seDecode;
                  var status = 'VAD';
                  var eventObj = {};
                  var kimino = [];
                  locationLoading = searates[i].port_loading;
                  locationdischarge = searates[i].port_discharge;
                    $.each(events,function(ikey,ival){
                        if(ival.id == shipid){
                          htmlOnestop='';
                          tTable ='';
                          catcher = 0;
                          htmlstat =`
                            <span>Location : ${ival.port_discharge}</span><br>
                            <span> Date : ${ival.eta}</span><br> 
                            <span> Status : ${statsCode[status]}</span><br>  
                            <hr>  
                          `;

                          var $headtitle='';
                          var etaetd ='';
                          var dcount = 0;
                          $.each(doublechecker,function(dkey,dval){
                           
                            if(dval.Vessel == ival.vessel_name && (dval.Voyage==ival.voyage_flight_num)){
                             var locfirstthree = onestopLoc[ival.port_discharge];
                             
                             tTable = `<h4>${dval.Vessel} - ${dval.Voyage}</h2><br>`;
                             
                             kimino = [];
                             $.each(dval,function(is,ks){
                              //if(ks != null){
                             
                                var addedClass ='';
                                if(catcher > 2){
                                  $headtitle = is.substring(0, 3); 
                                  etaetd +=ks;
                                  if(dcount == 4){
                                    dcount = 0;
                                    kimino = [];
                                  }
                                  kimino.push(ks);  
                                  eventObj[$headtitle] = kimino;
                                  dcount++;
                                }else{
                                  
                                } 
                             // }
    
                              catcher++;  
        
                             });
                             
                            } 
                          });
                          
                        }
                    });
                    
                    $.each(eventObj,function(ekey,eval){
                      htmlOnestop +=`<tr class="col"> <td>${ekey}</td>
                        <td>${(eval[0]!=null ? eval[0]  : 'no data')}</td>
                        <td>${(eval[1]!=null ? eval[1]  : 'no data')}</td>
                        <td>${(eval[2]!=null ? eval[2]  : 'no data')}</td>
                        <td>${(eval[3]!=null ? eval[3]  : 'no data')}</td>
                      </tr>
                      `;
                    });
                    if( $.isEmptyObject(eventObj) ){
                      $(rows).eq( i ).find('td').eq(7).find("span").hide();
                    }
                    
                    // if(eventObj.length == 0){
                    //   $(rows).eq( i ).find('td').eq(7).find("span").hide();
                    // }
                    $(rows).eq( i ).attr("data-details",shipid);
                    $(rows).eq( i ).after(
                        `<tr class="d-none ${shipid} ">
                          <td>${htmlstat}</td>
                        </tr>
                        <tr class="d-none  ot-${shipid}">
                          <td>
                            ${tTable}
                            <table class="w-100 table table-striped">
                              <thead>
                                  <tr>
                                      <th scope="col">Code</th>
                                      <th scope="col">ETA</th>
                                      <th scope="col">ATA</th>
                                      <th scope="col">AVA</th>
                                      <th scope="col">Stor</th>
                                  </tr>
                              </thead>
                              <tbody>
                                ${htmlOnestop}
                              </tbody>
                            
                            </table>
                            
                          </td>
                        </tr>`   
                    );
                  
                }
                
                last = group;
            }
            count++; 
        } );
    }
    }); 
    
    $('table').on('click','tr',function(e){
     var cl = $(this).attr("data-details");
     //$('.'+cl).toggleClass('d-none');
     var notincl = ['View','Details','Tracking'];
     var text = $(e.target).text();
     var attr = $(e.target).attr('data-header');
     var clId = $(e.target).attr('id');
     
      if($.inArray( text, notincl) == -1){
        if(attr ==='contnum'){
          $("#detailinfo .modal-body").html($('.'+clId+' td').html());
          $("#exampleModalCenterTitle").text('Movements Details');
          $('#detailinfo').modal('show');
        }
      }else{
        if(text ==='View'){
          
          $("#detailinfo .modal-body").html($('.ot-'+cl+' td').html());
          $("#exampleModalCenterTitle").text('One Stop Details');
          $('#detailinfo').modal('show');
        }
      }
      
     
    });
    
    $('.box-search').on('click',function(){
        console.log($(this).attr('id'));
        table.columns(0).search($(this).attr('id')).draw();
       // table.columns(0).search(""); 
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
  
    var mymap = L.map('mapid',{zoomControl:false}).setView([10.3130247, 123.9471531], 5);
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

      $('.showmore').on('click',function(){
        var att = $(this).attr('data-show').replace(" ","");
        setTimeout(function(){
          if($('#'+att).hasClass('d-none')){
            $('#'+att).removeClass('d-none');
            $(this).text('hide &#94;');
          }else{
            $('#'+att).addClass('d-none');
            $(this).text('Show More ');
          }
        },50); 
      });
});  
