
$(document).ready(function(){

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
    // chart.seriesContainer.draggable = false;
    // chart.seriesContainer.resizable = false;

    /* Create hover state and set alternative fill color */
    var hs = polygonTemplate.states.create("hover");
    hs.properties.fill = am4core.color("#367B25");

    // Remove Antarctica
    polygonSeries.exclude = ["AQ"];

    // Add some data
    // polygonSeries.data = [{
    //         "id": "US",
    //         "name": "United States",
    //         "value": 100
    //     }, {
    //         "id": "FR",
    //         "name": "France",
    //         "value": 50
    // }];
    var cities = chart.series.push(new am4maps.MapImageSeries());
    cities.mapImages.template.nonScaling = true;

    var city = cities.mapImages.template.createChild(am4core.Circle);
    city.radius = 6;
    city.fill = chart.colors.getIndex(0).brighten(-0.2);
    city.strokeWidth = 2;
    city.stroke = am4core.color("#fff");
    // Add line series
    var lineSeries = chart.series.push(new am4maps.MapLineSeries());
    lineSeries.mapLines.template.line.strokeWidth = 2;
    lineSeries.mapLines.template.line.strokeOpacity = 0.5;
    lineSeries.mapLines.template.line.stroke = city.fill;
    lineSeries.mapLines.template.line.nonScalingStroke = true;
    //lineSeries.mapLines.template.line.strokeDasharray = "1,1";
    lineSeries.zIndex = 10;

    // Create image series
    var imageSeries = chart.series.push(new am4maps.MapImageSeries());

    // Lets mouse hover over tooltip without it vanishing
    imageSeries.tooltip.keepTargetHover = true;

    // Enables interactivity of the tooltips label elements
    imageSeries.tooltip.label.interactionsEnabled = true;

    // Create a circle image in image series template so it gets replicated to all new images
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

    var line = lineSeries.mapLines.create();

    // Add a map object to line
    var arrow = line.arrow;
    arrow.color = am4core.color("#00ff00");
    arrow.position = 1;
    arrow.nonScaling = true;

    var test = [];
    test.push(pointObject);
    line.multiGeoLine = test;

    // Add a map object to line
    var bullet = line.lineObjects.create();
    bullet.nonScaling = true;
    bullet.position = 0;
    bullet.width = 48;
    bullet.height = 48;

    var plane = bullet.createChild(am4core.Sprite);
    //var plane = bullet.createChild(am4core.Image);
    //plane.href = "svgs/freight/"+transImage+".svg";
    plane.scale = 0.004;
    plane.horizontalCenter = "middle";
    plane.verticalCenter = "middle";
    plane.path = "M1320 5720 l0 -320 -301 0 c-316 0 -329 -2 -329 -45 0 -22 -281 -679 -292 -683 -4 -2 -8 -12 -8 -22 0 -10 -4 -20 -10 -22 -5 -1 -14 -23 -19 -48 -5 -25 -37 -120 -70 -212 -119 -327 -164 -544 -164 -798 0 -256 36 -436 159 -795 41 -121 78 -232 81 -248 3 -15 9 -27 14 -27 5 0 9 -10 9 -22 0 -13 7 -36 16 -53 17 -33 261 -631 278 -682 6 -18 14 -33 18 -33 5 0 8 -7 8 -15 0 -13 41 -15 305 -15 l305 0 0 -290 0 -290 405 0 405 0 0 290 0 290 3510 0 3510 0 29 28 c16 15 161 103 323 196 623 361 1044 620 1308 806 67 47 142 100 168 117 26 18 79 61 117 95 197 176 309 313 355 435 28 72 37 185 20 261 -28 132 -149 307 -313 454 -50 45 -99 90 -108 100 -9 10 -19 18 -22 18 -3 0 -63 44 -133 97 -245 184 -646 442 -1289 828 -209 126 -384 235 -388 242 -4 7 -16 13 -27 13 -10 0 -20 6 -22 13 -4 9 -719 13 -3521 15 l-3517 2 0 320 0 320 -405 0 -405 0 0 -320z m6760 -1005 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-5140 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m400 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m380 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m2470 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m450 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m1830 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-2740 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m1460 -230 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m390 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-2430 -80 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-860 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m430 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m5007 -141 c151 -31 260 -90 368 -199 283 -286 231 -746 -110 -974 -351 -234 -843 -112 -1025 255 -66 132 -85 268 -56 404 76 357 451 591 823 514z m-1647 -279 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-4340 -20 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m4760 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-5590 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m420 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m3330 -40 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-980 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m510 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m1420 -420 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-2470 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m2070 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-2510 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-440 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-530 -320 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-410 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-430 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m5180 -160 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m400 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-1800 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-1000 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m480 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-970 -290 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-500 -20 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-450 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m2940 -110 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m410 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-3850 -160 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-410 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-450 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205zM9435 3788 c-3 -8 -4 -97 -3 -198 l3 -185 40 0 40 0 3 83 3 82 74 0 75 0 0 -86 0 -85 38 3 37 3 0 195 0 195 -37 3 -38 3 0 -80 0 -81 -75 0 -74 0 -3 78 -3 77 -38 3 c-24 2 -39 -1 -42 -10z";
    plane.fill = chart.colors.getIndex(2).brighten(-0.2);
    plane.strokeOpacity = 0;
    // plane.path = "";
    //plane.path = "M272 288h-64C163.8 288 128 323.8 128 368C128 376.8 135.2 384 144 384h192c8.836 0 16-7.164 16-16C352 323.8 316.2 288 272 288zM240 256c35.35 0 64-28.65 64-64s-28.65-64-64-64c-35.34 0-64 28.65-64 64S204.7 256 240 256zM496 320H480v96h16c8.836 0 16-7.164 16-16v-64C512 327.2 504.8 320 496 320zM496 64H480v96h16C504.8 160 512 152.8 512 144v-64C512 71.16 504.8 64 496 64zM496 192H480v96h16C504.8 288 512 280.8 512 272v-64C512 199.2 504.8 192 496 192zM384 0H96C60.65 0 32 28.65 32 64v384c0 35.35 28.65 64 64 64h288c35.35 0 64-28.65 64-64V64C448 28.65 419.3 0 384 0zM400 448c0 8.836-7.164 16-16 16H96c-8.836 0-16-7.164-16-16V64c0-8.838 7.164-16 16-16h288c8.836 0 16 7.162 16 16V448z";
    // plane.fill = am4core.color("#3e96e0");
    // plane.strokeOpacity = 0;

    function animateMarker() {
        var from = bullet.position, to;
        if (from == 0) {
            to = 1;
            plane.rotation = 0;
        }
        else {
            to = 0;
            plane.rotation = 180;
        }
        
        var animation = bullet.animate({
            from: from,
            to: to,
            property: "position"
        }, 5000, am4core.ease.sinInOut);
        //animation.events.on("animationended", animateMarker)
    }
    // polygonSeries.dataFields.zoomLevel = 2.5;
    // polygonSeries.dataFields.zoomGeoPoint = {
    //     latitude: pointObject[0].latitude,
    //     longitude: pointObject[1].longitude
    // };
    // zoomed when load
    chart.homeZoomLevel = 1;
    chart.homeGeoPoint = {
        latitude: pointObject[0].latitude,
        longitude: pointObject[0].longitude
    };
    chart.events.on( "ready", function(){
        $.each(pointObject,function(zoomkey,zoomval){
            chart.zoomToGeoPoint({latitude:zoomval.latitude,longitude:zoomval.longitude},1.5);
        });
        
        animateMarker();
        imageSeries.data = pointObject;
        am4core.options.autoDispose = true;
        marker.tooltipHTML = tooltipHTML;
        
    });
});
});