$(document).ready(function(){
    var addlinearray = [];
   console.log(pointObject);
    am4core.ready(function() {
        // Create map instance
        var chart = am4core.create("chartdiv", am4maps.MapChart);
      
        // Set map definition
        chart.geodata = am4geodata_worldLow;
      
        // Set projection
        chart.projection = new am4maps.projections.Miller();
      
        // Create map polygon series
        var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
      
        // Make map load polygon (like country names) data from GeoJSON
        polygonSeries.useGeodata = true;
      
        // Configure series
        var polygonTemplate = polygonSeries.mapPolygons.template;
        polygonTemplate.tooltipText = "{name}";
        // polygonTemplate.fill = am4core.color("#74B266");
        polygonTemplate.events.on("hit", function(ev) {
            ev.target.series.chart.zoomToMapObject(ev.target);
        });
      
        /* Create hover state and set alternative fill color */
        var hs = polygonTemplate.states.create("hover");
        hs.properties.fill = am4core.color("#367B25");
      
        // Remove Antarctica
        polygonSeries.exclude = ["AQ"];
      
      // Create image series
      var imageSeries = chart.series.push(new am4maps.MapImageSeries());
        
      var imageSeriesTemplate = imageSeries.mapImages.template;
    // var circle = imageSeriesTemplate.createChild(am4core.Circle);
    // circle.radius = 8;
    // circle.fill = am4core.color("#007bff");
    // circle.stroke = am4core.color("#FFFFFF");
    // circle.strokeWidth = 3;
    // circle.nonScaling = true;
    // circle.tooltipText = "{title}";
    var marker = imageSeriesTemplate.createChild(am4core.Image);
    marker.href = "https://s3-us-west-2.amazonaws.com/s.cdpn.io/t-160/marker.svg";
    marker.width = 20;
    marker.height = 20;
    marker.color = "#333";
    marker.fill = am4core.color("#007bff");
    marker.nonScaling = true;
    // marker.tooltipText = "{title}";
    
    marker.horizontalCenter = "middle";
    marker.verticalCenter = "bottom";

    // Set property fields
    imageSeriesTemplate.propertyFields.latitude = "latitude";
    imageSeriesTemplate.propertyFields.longitude = "longitude";
       
    // Add line series
    var lineSeries = chart.series.push(new am4maps.MapLineSeries());
    lineSeries.mapLines.template.strokeWidth = 2;
    lineSeries.mapLines.template.stroke = am4core.color("#00ff00");
    lineSeries.mapLines.template.nonScalingStroke = true;
    // lineSeries.mapLines.template.line.strokeOpacity = 0.5;
    // lineSeries.mapLines.template.line.strokeDasharray = "3,3";
        
        // Add line bullets
    var cities = chart.series.push(new am4maps.MapImageSeries());
    cities.mapImages.template.nonScaling = true;
      
    var city = cities.mapImages.template.createChild(am4core.Circle);
    city.radius = 0;
    city.fill = chart.colors.getIndex(0).brighten(-0.2);
    city.strokeWidth = 2;
    city.stroke = am4core.color("#fff");
    
    function addCity(coords, title) {
        var city = cities.mapImages.create();
        city.latitude = coords.latitude;
        city.longitude = coords.longitude;
        city.tooltipText = title;
        return city;
    }
     
      // Add lines
      var lineSeries = chart.series.push(new am4maps.MapArcSeries());
      lineSeries.mapLines.template.line.strokeWidth = 2;
      lineSeries.mapLines.template.line.strokeOpacity = 0.5;
      lineSeries.mapLines.template.line.stroke = city.fill;
      lineSeries.mapLines.template.line.nonScalingStroke = true;
      lineSeries.mapLines.template.line.strokeDasharray = "1,1";
      lineSeries.zIndex = 10;
      
      var shadowLineSeries = chart.series.push(new am4maps.MapLineSeries());
      shadowLineSeries.mapLines.template.line.strokeOpacity = 0;
      shadowLineSeries.mapLines.template.line.nonScalingStroke = true;
      shadowLineSeries.mapLines.template.shortestDistance = false;
      shadowLineSeries.zIndex = 5;
      
      function addLine(from, to) {
          var line = lineSeries.mapLines.create();
          line.imagesToConnect = [from, to];
          line.line.controlPointDistance = -0.3;
      
          var shadowLine = shadowLineSeries.mapLines.create();
          shadowLine.imagesToConnect = [from, to];
      
          return line;
      }
      
      $.each(groupArrayOfObjects(pointObject,"order"),function(gkey,gval){
          if(gval.length > 1){
            var l1 = addCity({ "latitude":  gval[0].latitude, "longitude": gval[0].longitude }, tooltipHTML);
          var l2 = addCity({ "latitude": gval[1].latitude, "longitude": gval[1].longitude }, tooltipHTML);
          addlinearray.push({from:l1,to:l2});
          }
          
      });
      
      $.each(addlinearray,function(adkey,adval){
       //console.log(adval);
        addLine(adval['from'], adval['to']);
      });
      
      // Add plane
      var plane = lineSeries.mapLines.getIndex(0).lineObjects.create();
      plane.position = 0;
      plane.width = 48;
      plane.height = 48;
      
      plane.adapter.add("scale", function(scale, target) {
          return 0.5 * (1 - (Math.abs(0.5 - target.position)));
      })
      
      var planeImage = plane.createChild(am4core.Sprite);
      planeImage.scale = 0.2;
      planeImage.horizontalCenter = "middle";
      planeImage.verticalCenter = "middle";
      planeImage.path = "m2,106h28l24,30h72l-44,-133h35l80,132h98c21,0 21,34 0,34l-98,0 -80,134h-35l43,-133h-71l-24,30h-28l15,-47";
      planeImage.fill = chart.colors.getIndex(2).brighten(-0.2);
      planeImage.strokeOpacity = 0;
      
      var shadowPlane = shadowLineSeries.mapLines.getIndex(0).lineObjects.create();
      shadowPlane.position = 0;
      shadowPlane.width = 48;
      shadowPlane.height = 48;
      
      var shadowPlaneImage = shadowPlane.createChild(am4core.Sprite);
      shadowPlaneImage.scale = 0.01;
      shadowPlaneImage.horizontalCenter = "middle";
      shadowPlaneImage.verticalCenter = "middle";
      shadowPlaneImage.path = "m2,106h28l24,30h72l-44,-133h35l80,132h98c21,0 21,34 0,34l-98,0 -80,134h-35l43,-133h-71l-24,30h-28l15,-47";
      shadowPlaneImage.fill = am4core.color("#000");
      shadowPlaneImage.strokeOpacity = 0;
      
      shadowPlane.adapter.add("scale", function(scale, target) {
          target.opacity = (0.6 - (Math.abs(0.5 - target.position)));
          return 0.5 - 0.3 * (1 - (Math.abs(0.5 - target.position)));
      })
      
      // Plane animation
      var currentLine = 0;
      var direction = 1;
      function flyPlane() {
          // Get current line to attach plane to
          plane.mapLine = lineSeries.mapLines.getIndex(currentLine);
          plane.parent = lineSeries;
          shadowPlane.mapLine = shadowLineSeries.mapLines.getIndex(currentLine);
          shadowPlane.parent = shadowLineSeries;
          shadowPlaneImage.rotation = planeImage.rotation;
      
          // Set up animation
          var from, to;
          var numLines = lineSeries.mapLines.length;
          if (direction == 1) {
              from = 0
              to = 1;
              if (planeImage.rotation != 0) {
                  planeImage.animate({ to: 0, property: "rotation" }, 1000).events.on("animationended", flyPlane);
                  return;
              }
          }
          else {
              from = 1;
              to = 0;
              if (planeImage.rotation != 180) {
                  planeImage.animate({ to: 180, property: "rotation" }, 1000).events.on("animationended", flyPlane);
                  return;
              }
          }
      
          // Start the animation
          var animation = plane.animate({
              from: from,
              to: to,
              property: "position"
          }, 5000, am4core.ease.sinInOut);
          animation.events.on("animationended", flyPlane)
      
          shadowPlane.animate({
              from: from,
              to: to,
              property: "position"
          }, 5000, am4core.ease.sinInOut);
      
          // Increment line, or reverse the direction
          currentLine += direction;
          if (currentLine < 0) {
              currentLine = 0;
              direction = 1;
          }
      
      }
        chart.homeZoomLevel = 1;
        chart.homeGeoPoint = {
            latitude: pointObject[0].latitude,
            longitude: pointObject[0].longitude
        };
        chart.events.on( "ready", function(){
            $.each(pointObject,function(zoomkey,zoomval){ 
                chart.zoomToGeoPoint({latitude:zoomval.latitude,longitude:zoomval.longitude},1.5);
            });
          
            flyPlane();
            imageSeries.data = pointObject;
            am4core.options.autoDispose = true;
            marker.tooltipHTML = tooltipHTML;

            
        });
        marker.tooltipHTML = tooltipHTML;
      });
});
function groupArrayOfObjects(list, key) {
    return list.reduce(function(rv, x) {
      (rv[x[key]] = rv[x[key]] || []).push(x);
      return rv;
    }, {});
  };