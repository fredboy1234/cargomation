jQuery(document).ready(function() {
   
    var groupColumn = 0;
    var indexCollection = [];
    var earlyCheck = [];
    var polyArray = [];
    $('.table thead th').each( function () {
      var title = $(this).text();
      //console.log(title);
      if($.trim(title) !== 'Action'){
        $(this).append( '<input type="text" placeholder="Search '+title+'" />' );
      }
      
    });
    $('#datefilter').daterangepicker({
      ranges: {
          'Today': [moment(), moment()],
          'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days': [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      startDate: moment(),
  }, function(start, end, label) {
      console.log('New date range selected: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD') + ' (predefined range: ' + label + ')');
  }); 

    $('table').on('mouseover','tr',function(e){
      if($(e.target).hasClass('mmatchhover')){
        $(this).css({"z-index":"100"});
        var lID = $(this).find('.mmatchhover').attr('data-match');
        $('#'+lID).removeClass('d-none');
      }else if($(e.target).hasClass('mmatchhovervsl')){
        $(this).css({"z-index":"100"});
        var xlID = $(this).find('.mmatchhovervsl').attr('data-match');
        $('#'+xlID).removeClass('d-none');
      }else if($(e.target).hasClass('mmatchVo')){
        $(this).css({"z-index":"100"});
        var xlID = $(this).find('.mmatchVo').attr('data-match');
        $('#'+xlID).removeClass('d-none');
      }else if($(e.target).hasClass('mismatchLoc')){
        $(this).css({"z-index":"100"});
        var xlID = $(this).find('.mismatchLoc').attr('data-match');
        $('#'+xlID).removeClass('d-none');
      }
    }).on('mouseleave','tr',function(e){
      var lID = $(this).find('.mmatchhover').attr('data-match');
      $('#'+lID).addClass('d-none');
      var xlID = $(this).find('.mmatchhovervsl').attr('data-match');
      $('#'+xlID).addClass('d-none');
      var vlID = $(this).find('.mmatchVo').attr('data-match');
        $('#'+vlID).addClass('d-none');
        var loclID = $(this).find('.mismatchLoc').attr('data-match');
        $('#'+loclID).addClass('d-none');

      if($(e.target).hasClass('mmatchhover')){
        var lID = $(this).find('.mmatchhover').attr('data-match');
        $('#'+lID).addClass('d-none');
      }else if($(e.target).hasClass('mmatchhovervsl')){
        var xlID = $(this).find('.mmatchhovervsl').attr('data-match');
        $('#'+xlID).addClass('d-none');
      }else if($(e.target).hasClass('mmatchVo')){
       
        var vlID = $(this).find('.mmatchVo').attr('data-match');
        $('#'+vlID).addClass('d-none');
      }else if($(e.target).hasClass('mismatchLoc')){
        var loclID = $(this).find('.mismatchLoc').attr('data-match');
        $('#'+loclID).addClass('d-none');
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
         { data: "shipping_line" },
            
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
                            earlyCheck =[];
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
                      var tbletd = '';
                      var datehandler = '';

                      $.each(eval,function(xco,xcoh){
                        if(!isNaN(Date.parse(xcoh))){
                          earlyCheck.push(Date.parse(xcoh));
                        }
                      });

                      $.each(eval,function(co,coh){
                        var reta = Date.parse(coh);
                        datehandler = Math.min.apply(Math,earlyCheck);
                        if(datehandler === reta){
                          tbletd += `<td class="text-success">${(coh!=null ? coh  : 'no data')}</td>`;  
                        }else{
                          tbletd += `<td>${(coh!=null ? coh  : 'no data')}</td>`;  
                        }
                        
                      }); 

                      htmlOnestop +=`<tr class="col"> <td>${ekey}</td>
                        ${tbletd}
                      </tr>`;
                      tbletd = '';
                      earlyCheck = [];
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

    if(typeof searates.data !== 'undefined'){
     
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
    }else{ 
      var gkey = '&key=AIzaSyA89i4Tuzrby4Dg-ZxnelPs-U3uvHoR9eo';
      var gaddress = 'address=';
      var greq = 'https://maps.googleapis.com/maps/api/geocode/json?';
      $.each(searates,function(okey,oval){
        var legorder = JSON.parse(oval.route_leg);
        var legcollection =[];
 
        console.log(legorder);
        $.each(legorder, function(legkey,legval){
          legcollection.push(legval.Destination,legval.Origin);
          $.each(legcollection,function(colkey,colval){
              $.get(greq+'address='+colval+gkey, function(data){  
                if(data.status == 'OK'){
                 var lat = data.results[0].geometry.location.lat;
                 var long = data.results[0].geometry.location.lng;
                 
                    polyArray.push([lat,long]);
                    L.marker([lat,long]).bindPopup(
                        '<div class="text-center">'
                        +'<img class="img-thumbnail" src="https://www.myshiptracking.com/requests/getimage-small/271045135.jpg"><br><br>'
                        +'<span>Origin: '+legval.Origin+'</span><br>'
                        +'<span>Destination: '+legval.Destination+'</span><br>'
                        +'<span>Vessel Name:  '+legval.VesselName+'</span><br>'
                        +'</div>'
                    ).addTo(mymap);
                  
                }
              }); 
          });
          legcollection = [];
        });
      });
    }

    var mymap = L.map('mapid',{zoomControl:false}).setView([10.3130247, 123.9471531], 5);
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
    L.tileLayer('https://{s}.tile.jawg.io/jawg-matrix/{z}/{x}/{y}{r}.png?access-token=iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC', {
      attribution: '<a href="http://jawg.io" title="Tiles Courtesy of Jawg Maps" target="_blank">&copy; <b>Jawg</b>Maps</a> &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
      minZoom: 0,
      maxZoom: 13,
      subdomains: 'abcd',
      accessToken: 'iPr7S2yMM5rvXzDFNlFW35qgk2HTvVSuZTgY6EWcMYgYknPfEnPYAhIbB366OUeC'
    }).addTo(mymap);
   
   
    
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

}); 

$(document).ready(function () {
  invokeFilter("", 1);
  
  $("#add_filters").on("change",function (e) { 
    var selected = $(this).find('option:selected').val();
    addSearchFilter(selected);
    console.log(selected);
  });
  // Delete the form fieed row
  $("body").on("click", ".remove_node_btn_frm_field", function () {
    $(this).closest(".form_field_outer_row").remove();
    $(this).closest(".form_field_outer_row").attr('trashid');
    var elem = $(".form_field_outer").find(".form_field_outer_row");
    if(elem.length == 1) {
      elem.find('[id^=no_cond]').prop("disabled", true).addClass('exclude');
    } else {
      $('#no_cond_'+elem.length).prop("disabled", true).addClass('exclude');
    }
    console.log("success");
  });
  // Clone method
  $("body").on("click", ".add_node_btn_frm_field", function (e) {
    e.preventDefault();
    //var index = $(e.target).closest(".form_field_outer").find(".form_field_outer_row").length + 1;
    var index = $(".form_field_outer .form_field_outer_row").map(function() {
      return parseFloat($(this).attr('section'))+1;
    }).get().sort().pop();
    
    var cloned_el = $(e.target).closest(".form_field_outer_row").clone(true);

    $(e.target).closest(".form_field_outer").last().append(cloned_el).find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);

    $(e.target).closest(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);
   
    //change id
   
    
    // $(e.target)
    //   .closest(".form_field_outer")
    //   .find(".form_field_outer_row")
    //   .last()
    //   .find("input[type='text']")
    //   .attr("id", "mobileb_no_" + index);

    // $(e.target)
    //   .closest(".form_field_outer")
    //   .find(".form_field_outer_row")
    //   .last()
    //   .find("select")
    //   .attr("id", "no_type_" + index);
    
    $(cloned_el).attr("section",index);
    $(cloned_el).find('optgroup option').attr("data-index",index);
    $(cloned_el).find(".search-list").attr("id","no_search_"+index);
    $(cloned_el).find("select[name='type[]']").attr("id","no_type_"+index);
    $(cloned_el).find("input[name='value[]']").attr("id","no_value_"+index);
    $(cloned_el).find(".add_node_btn_frm_field").attr("section",index);
    $(e.target).closest(".form_field_outer").find(".exclude").attr("disabled",false);
    $(e.target).closest(".form_field_outer").find(".exclude").last().attr("disabled",true);

    var sdex = $(this).attr("section");
    console.log(sdex);
    var searchValue = $(e.target).closest(".form_field_outer").find("#no_search_"+sdex+" option:selected").val();
    $(cloned_el).find(".search-list option[value='"+searchValue+"']").attr("selected", true);
    
    //count++;
  });
});

$("input[name='value[]'], .add_node_btn_frm_field").keypress(function(e){
  if(e.which ==13){
    e.preventDefault();
    e.stopPropagation();
  }
});
function invokeFilter(selected, index) {
  var $select = $(`#add_filters, #no_search_${index}`); 
  var text = '<option value="" selected="" disabled="" hidden="">Add search option</option>';
  $.getJSON('/settings/search-vessel.json', function(data) {
  $.each(data, function(key, value) {
    text += `<optgroup label="${key}">`;
    $.each(value, function(key2, value2) {
      text += `<option data-type="${value2.filterType}" value="${value2.filterName}" data-index="${index}" `;
        if(value2.filterName == selected) {
          text += `selected`;
        }
      text += `>${value2.filterID}</option>`;
    });
    text += `</optgroup>`;
  });
    $select.html(text);
    $("#add_filters").val("");
  }).fail(function(){
    console.log("Error");
  });
}

function addSearchFilter(selected) {  
  $(".form_field_outer").find(".exclude").prop("disabled", false).removeClass('exclude');
  //var index = $(".form_field_outer").find(".form_field_outer_row").length + 1;
  var index = $(".form_field_outer .form_field_outer_row").map(function() {
        return parseFloat($(this).attr('section'))+1;
    }).get().sort().pop();
  $(".form_field_outer").append(`
  <div class="row form_field_outer_row ${index}" section="${index}">
    <div class="form-group col-md-3">
      <select name="search[]" id="no_search_${index}" class="form-control search-list" data-index="${index}">
        <option>--Select type--</option>
      </select>
    </div>
    <div class="form-group col-md-2">
      <select name="type[]" id="no_type_${index}" class="form-control no_type">
        <option>--Select type--</option>
      </select>
    </div>
    <div class="form-group col-md-4">
      <input name="value[]" id="no_value_${index}" type="text" class="form-control w_90" placeholder="Enter search value" />
    </div>
    <div class="form-group col-md-1">
      <select name="cond[]" id="no_cond_${index}" class="form-control exclude" disabled>
        <option value="OR">OR</option>
        <option value="AND">AND</option>
      </select>
    </div>
    <div class="form-group col-md-2 add_del_btn_outer">
      <button class="btn_round add_node_btn_frm_field" title="Copy or clone this row" section="${index}">
        <i class="fas fa-copy"></i>
      </button>

      <button class="btn_round remove_node_btn_frm_field" disabled>
        <i class="fas fa-trash-alt"></i>
      </button>
    </div>
  </div>
  `);
  $(".form_field_outer").find(".remove_node_btn_frm_field:not(:first)").prop("disabled", false);
  $(".form_field_outer").find(".remove_node_btn_frm_field").first().prop("disabled", true);
  invokeFilter(selected, index);
}

$("#clearFilter").on("click",function(){
  $(".form_field_outer_row:not(:first)").remove();
  $("#no_value_1").val("");
  $("#no_search_1").val("");
  $("#no_type_1").val("");
  $("#add_filters option").each(function(){
    $(this).attr("data-index",1);
  });
});

$('#addvance-search-form').on("submit", function(ev) {
  ev.preventDefault();
  var allBlank = true;
    $('input',this).each(function(index, el){
      if ($(el).val().length != 0) allBlank = false; //they're not all blank anymore
    });
    if(allBlank){
      Swal.fire('Please Add Search Parameter!')
    }else{
      //table.ajax.reload(setColor);
    }
});

$(document).on("change", ".search-list", function(){
  var index = $('option:selected',this).attr('data-index');
  var dataType = $('option:selected',this).attr("data-type");
  var data = [];
  
  if($(this).hasClass("add_new_frm_field_btn")){
    index = parseInt(index)+1;
  }
 
  data['id'] = "no_type_"+index;
  //data['options'] = triggerType(dataType);
  data['value'] = index;
  data['type'] = dataType;

  triggerType(data);
  if(dataType !== "date"){
    $("#no_value_"+index).val("");
    var dateatt = $("#no_value_"+index).data('daterangepicker');
    if(typeof dateatt !== typeof undefined && dateatt !== false){
      $("#no_value_"+index).data('daterangepicker').remove();
    }
  }
 
 // appendToSelect(data);
});

function triggerType(data){
  switch(data['type']) {    
    case 'date':
      $("#no_value_"+data['value']).daterangepicker({
        locale: {
          format:defaultDate
        }
      });
    $('#'+data['id']).html(`
        <option class="datepick" data-date="${moment().format(defaultDate)}">Today</option>
        <option class="datepick" data-date="${moment().subtract(1, 'days').format(defaultDate)}">Yesterday</option>
        <option class="datepick" data-date="${moment().subtract(1, 'weeks').format(defaultDate)}">Last Week</option>
        <option class="datepick" data-date="${moment().subtract(7, 'days').format(defaultDate)}">Last 7 Days</option>
        <option class="datepick" data-date="${moment().subtract(14, 'days').format(defaultDate)}">Last 14 Days</option>
        <option class="datepick" data-date="${moment().subtract(30, 'days').format(defaultDate)}">Last 30 Days</option>
        <option class="datepick" data-date="${moment().subtract(1, 'month').format(defaultDate)}">Last Month</option>
        <option class="datepick" data-date="${moment().subtract(2, 'month').format(defaultDate)}">Last 2 Months</option>
        <option class="datepick" data-date="${moment().subtract(3, 'month').format(defaultDate)}">Last 3 Months</option>
        <option class="datepick" data-date="${moment().add(1, 'days').format(defaultDate)}">Tomorrow</option>
        <option class="datepick" data-date="${moment().add(1, 'weeks').format(defaultDate)}">Next Week</option>
        <option class="datepick" data-date="${moment().add(7, 'days').format(defaultDate)}">Next 7 Days</option>
        <option class="datepick" data-date="${moment().add(14, 'days').format(defaultDate)}">Next 14 Days</option>
        <option class="datepick" data-date="${moment().add(1, 'months').format(defaultDate)}">Next Month</option>
        <option class="datepick" data-date="${moment().add(2, 'months').format(defaultDate)}">Next 2 Months</option>
        <option class="datepick" data-date="${moment().add(6, 'months').format(defaultDate)}">Next 6 Months</option>
        <option class="datepick" data-date="${moment().add(12, 'months').format(defaultDate)}">Next  12 Months</option>`);
    break;
    case 'input':
      $('#'+data['id']).html(`<option value="exact" selected>Exact</option>
      <option value="starts_with">starts with</option>
      <option value="contains">contains</option>
      <option value="not_equal">not equal</option>
      <option value="not_starting">not starting</option>
      <option value="not_contain">not contain</option>
      <option value="is_blank">is blank</option>
      <option value="not_blank">is not blank</option>`);
    break;
    case 'option':
        $('#'+data['id']).html(`<option value="exact" selected>Exact</option>
      <option value="starts_with">starts with</option>
      <option value="contains">contains</option>
      <option value="not_equal">not equal</option>
      <option value="not_starting">not starting</option>
      <option value="not_contain">not contain</option>
      <option value="is_blank">is blank</option>
      <option value="not_blank">is not blank</option>`);
    break;
    }
}

$(document).on("change", "[id*='no_type_']",function(){
  if($("option:selected",this).hasClass("datepick")){
    var thisOption = $("option:selected",this);
    var startdate = thisOption.attr("data-date");
    var idOfDate = "#no_value_"+thisOption.parent().parent().parent().attr("section");
      $(idOfDate).daterangepicker({
        startDate:startdate,
        locale: {
          format: defaultDate
        }
      });
  }
});