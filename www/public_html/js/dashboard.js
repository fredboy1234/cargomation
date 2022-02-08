$(document).ready(function(){  
    $(".datepicker-days table").on('click',"td[data-action='selectDay']",function(){
        var day = $(this).data('day');
        window.location.href = "/doctracker?calendar="+day;
    }); 
    
    var preventer = [];
    var pointObject=[];

    $.each(port_loading_couint,function(okey,oval){
      var loading = oval.port_loading; 
      var ccount = oval.count;
      if(loading !==""){
        if ($.inArray(loading, preventer) == -1){
          preventer.push(loading);
          var mcolor ="#dc3545";
          if(oval.transport_mode === "Air"){
            mcolor = "#007bff";
          }else if(oval.transport_mode === "Sea"){
            mcolor = "#28a745";
          }
          var req1 = $.get('https://maps.googleapis.com/maps/api/geocode/json?address='+loading+'&key=AIzaSyA89i4Tuzrby4Dg-ZxnelPs-U3uvHoR9eo', function(data){ 
            var txtcontent = 'Location:'+loading+' Count:'+oval.count;
            var items = [50, 60, 80];
            var item = items[Math.floor(Math.random() * items.length)];
            
            if(data.status === 'OK'){
              var ellong = data.results[0].geometry.location.lng;
              var ellat = data.results[0].geometry.location.lat;
             // pointObject.push({long:ellong,lat:ellat,name:txtcontent});
              pointObject.push({
                title: txtcontent,
                latitude: ellat,
                longitude: ellong,
                color: mcolor
                });
            }
          });
        }  
      }
      
    });
    
    let map = new Map(pointObject,"chartdiv");
    map.executeMap();
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