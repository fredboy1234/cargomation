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
    
    // Themes begin
    am4core.useTheme(am4themes_animated);
    // Themes end
  
    // Create map instance
    var chart = am4core.create("chartdiv", am4maps.MapChart);
  
    // Set map definition
    chart.geodata = am4geodata_worldLow;
    chart.maxZoomLevel = 1;
    chart.seriesContainer.draggable = false;
    chart.chartContainer.wheelable = false;
    // Set projection
    chart.projection = new am4maps.projections.Miller();
  
    // Create map polygon series
    var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
  
    // Exclude Antartica
    polygonSeries.exclude = ["AQ"];
  
    // Make map load polygon (like country names) data from GeoJSON
    polygonSeries.useGeodata = true;
    
    // Configure series
    var polygonTemplate = polygonSeries.mapPolygons.template;
    polygonTemplate.tooltipText = "{name}";
    polygonTemplate.polygon.fillOpacity = 0.6;
    
    
    // Create hover state and set alternative fill color
    var hs = polygonTemplate.states.create("hover");
    hs.properties.fill = chart.colors.getIndex(0);
    
    // Add image series
    var imageSeries = chart.series.push(new am4maps.MapImageSeries());
    imageSeries.mapImages.template.propertyFields.longitude = "longitude";
    imageSeries.mapImages.template.propertyFields.latitude = "latitude";
    imageSeries.mapImages.template.tooltipText = "{title}";
    imageSeries.mapImages.template.propertyFields.url = "url";
    
    var circle = imageSeries.mapImages.template.createChild(am4core.Circle);
    circle.radius = 3;
    circle.propertyFields.fill = "color";
    circle.nonScaling = true;
    
    var circle2 = imageSeries.mapImages.template.createChild(am4core.Circle);
    circle2.radius = 3;
    circle2.propertyFields.fill = "color";
    
    
    circle2.events.on("inited", function(event){
        animateBullet(event.target);
    })
    
    
    function animateBullet(circle) {
        var animation = circle.animate([{ property: "scale", from: 1 / chart.zoomLevel, to: 5 / chart.zoomLevel }, { property: "opacity", from: 1, to: 0 }], 1000, am4core.ease.circleOut);
        animation.events.on("animationended", function(event){
            animateBullet(event.target.object);
        })
    }
    
    var colorSet = new am4core.ColorSet();
    
    setTimeout(function(){
        imageSeries.data = pointObject;
    },2000);
    // end am5.ready()
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