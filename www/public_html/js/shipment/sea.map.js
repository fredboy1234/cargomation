
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
    plane.scale = 1.9;
    plane.horizontalCenter = "middle";
    plane.verticalCenter = "middle";
    plane.path = "m16 31.36c-0.064 0-0.128-0.018-0.185-0.052-0.211-0.126-5.175-3.152-5.175-7.308v-20c0-0.071 0.021-0.141 0.061-0.2l2-3c0.066-0.1 0.178-0.16 0.299-0.16h6c0.12 0 0.232 0.06 0.3 0.16l2 3c0.039 0.059 0.06 0.129 0.06 0.2v20c0 4.156-4.964 7.182-5.175 7.309-0.057 0.034-0.121 0.051-0.185 0.051zm-4.64-9v1.64c0 3.321 3.754 5.989 4.64 6.573 0.886-0.584 4.64-3.252 4.64-6.573v-1.64h-9.28zm8-0.72h1.279v-3.28h-1.279v3.28zm-2 0h1.279v-7.28h-1.279v7.28zm-2 0h1.28v-3.28h-1.28v3.28zm-2 0h1.28v-3.28h-1.28v3.28zm-2 0h1.28v-7.28h-1.28v7.28zm8-4h1.279v-11.28h-1.279v11.28zm-4 0h1.28v-7.28h-1.28v7.28zm-2 0h1.28v-3.28h-1.28v3.28zm4-4h1.279v-3.28h-1.279v3.28zm-4 0h1.28v-7.28h-1.28v7.28zm-2 0h1.28v-3.28h-1.28v3.28zm6-4h1.279v-3.28h-1.279v3.28zm-2 0h1.28v-3.28h-1.28v3.28zm-4 0h1.28v-3.28h-1.28v3.28zm0-4h9.28v-1.531l-1.832-2.749h-0.448v1.64c0 0.199-0.161 0.36-0.36 0.36h-4c-0.199 0-0.36-0.161-0.36-0.36v-1.64h-0.447l-1.833 2.749v1.531zm3-3h3.28v-1.28h-3.28v1.28zm3.14 23.72h-3c-0.199 0-0.36-0.161-0.36-0.36v-0.64h-1.14v-0.72h1.14v-0.64c0-0.199 0.161-0.36 0.36-0.36h3c0.199 0 0.36 0.161 0.36 0.36v0.64h1.14v0.721h-1.14v0.639c0 0.199-0.161 0.36-0.36 0.36zm-2.64-0.72h2.28v-1.28h-2.28v1.28z";
    plane.fill = chart.colors.getIndex(2).brighten(-0.2);
    plane.strokeOpacity = 0;
    plane.rotation = -90;
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