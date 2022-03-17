$(window).on("load", function() {
    //$("#lazy-tablue .card").lazy();
   
    // The Calender
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
    
    var preventer = [];
    var pointObject=[];
    var counter =0;
    var stoper = 0;
    var promises = [];
    var tooltipHTML = '<p>Test from test</p>';

    setTimeout(function(){
      if (window.jQuery) {  
     
        $.each(port_loading_couint,function(okey,oval){
          var loading = oval.port_loading; 
          var ccount = oval.count;
      
          if(loading !==""){
            // if ($.inArray(loading, preventer) == -1){
              //preventer.push(loading);
              var mcolor ="#dc3545";
      
              if(oval.transport_mode === "Air"){
                mcolor = "#007bff";
              }else if(oval.transport_mode === "Sea"){
                mcolor = "#28a745";
              }
              var txtcontent = '';
    
              if ($.inArray(loading, preventer) == 0){
                $.grep(port_loading_couint, function(obj) {
                  if(obj.port_loading === loading){  
                    txtcontent += `<span><strong>Location:</strong> ${loading}</span><br>
                      <span><strong>Shipment Count:</strong> ${obj.count}</span><br>
                      <span><strong>Type:</strong>${obj.transport_mode}</span><br><hr>`;
                  }
                });
              }else{ 
                txtcontent=`<span><strong>Location:</strong> ${loading}</span><br>
                  <span><strong>Shipment Count:</strong> ${oval.count}</span><br>
                  <span><strong>Type:</strong>${oval.transport_mode}</span>`;
              }
              preventer.push(loading);
              var items = [50, 60, 80];
              var item = items[Math.floor(Math.random() * items.length)];
              var data = [];
              var promise = $.ajax({
                  url: document.location.origin + '/shipment/getCity/',
                  type: "POST",
                  dataType: "json",
                  data: { location: loading },
                  success: function (res) {
                      data = res;
                      if(data.length > 0) {
                        var latitude = parseFloat( data[0].lat);
                        var longitude = parseFloat(data[0].lng);
                        pointObject.push({
                          title: txtcontent,
                          latitude: latitude,
                          longitude: longitude,
                          color: mcolor,
                          idport: loading
                          });
                          counter++;
                      }
                  }
              }); 
           // }  
          }
          promises.push(promise);
        });
    
        $.when.apply($, promises).done(function() {
          let map = new Map(pointObject,"chartdiv");
          map.executeMap();
        }).fail(function() {
          console.log("fail");
        });
        
        var divElement=document.getElementById("viz1646066982039"),
                        vizElement=divElement.getElementsByTagName("object")[0];800<divElement.offsetWidth||500<divElement.offsetWidth?(vizElement.style.width="100%",
                        vizElement.style.height=.75*divElement.offsetWidth+"px"):(vizElement.style.width="100%",vizElement.style.height="2627px");
                        var scriptElement=document.createElement("script");
                        scriptElement.src="https://public.tableau.com/javascripts/api/viz_v1.js",vizElement.parentNode.insertBefore(scriptElement,vizElement)
      }
    },5000);
    
  
});

//on click wont work need to check so we use this javascript temporarily
function tablueshipment(){
    $('.navshipment').addClass('show active');
    $('.navcontainer').removeClass('show active');
    $('.navimport').removeClass('show active');
}
function tablueContainer(){
    $('.navcontainer').addClass('show active');
    $('.navshipment').removeClass('show active');
    $('.navimport').removeClass('show active');
}
function tablueImport(){
    $('.navimport').addClass('show active');
    $('.navshipment').removeClass('show active'); 
    $('.navcontainer').removeClass('show active');
}
function dashChart(id,bgcolor,data){
    var ctx = document.getElementById(id).getContext('2d');
    var labelTime = ['12am','1am','2am','3am','4am','5am','6am','7am','8am','9am','10am','11am'];
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
           labels: labelTime,
           datasets: [{
               label:"",
                data: data,
                backgroundColor: bgcolor
           }]
        },
        options: {
            legend: {
                display: false,
            },
           scales: {
                xAxes: [{
                   gridLines: {
                      display: false,
                   },
                   ticks: {
                    display: false,
                    autoSkip: false
                   }  
                }],
                yAxes: [{
                   gridLines: {
                      display: false
                   },
                   ticks: {
                    display: false
                   }  
                }]
           }
        }
    });
    
    if(theme == 'template_one' || theme == 'template_three'){
        $('.table').removeClass("table-sm").addClass("table-md");
    }
}