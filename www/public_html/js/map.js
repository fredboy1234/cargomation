function Map(data,mcontainer){
    let pointObject = data;
    let mapcontainer = mcontainer;
        // Themes begin
        am4core.useTheme(am4themes_animated);
        // Themes end

        // Create map instance
        var chart = am4core.create(mapcontainer, am4maps.MapChart);

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

        this.executeMap = function(){
            setTimeout(function(){
                imageSeries.data = pointObject;
            },2000);
        }
}

function simpleMap(data, mcontainer){
    let pointObject = data;
    let mapcontainer = mcontainer;

    // Create map instance
    var chart = am4core.create(mapcontainer, am4maps.MapChart);

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

    // Add some data
    // polygonSeries.data = [{
    // "id": "US",
    // "name": "United States",
    // "value": 100
    // }, {
    // "id": "FR",
    // "name": "France",
    // "value": 50
    // }];

    // Create image series
    var imageSeries = chart.series.push(new am4maps.MapImageSeries());

    // Create a circle image in image series template so it gets replicated to all new images
    var imageSeriesTemplate = imageSeries.mapImages.template;
    var circle = imageSeriesTemplate.createChild(am4core.Circle);
    circle.radius = 8;
    circle.fill = am4core.color("#007bff");
    circle.stroke = am4core.color("#FFFFFF");
    circle.strokeWidth = 3;
    circle.nonScaling = true;
    circle.tooltipText = "{title}";

    // Set property fields
    imageSeriesTemplate.propertyFields.latitude = "latitude";
    imageSeriesTemplate.propertyFields.longitude = "longitude";

    // Add line series
    var lineSeries = chart.series.push(new am4maps.MapLineSeries());
    lineSeries.mapLines.template.strokeWidth = 4;
    lineSeries.mapLines.template.stroke = am4core.color("#e03e96");
    lineSeries.mapLines.template.nonScalingStroke = true;
    var geoLine = [];
    geoLine.push(pointObject);
    // line.multiGeoLine = geoLine;
    // [
    //     [
    //         {
    //             "latitude": -33.8688197,
    //             "longitude": 151.2092955,
    //             "title": "Sydney",
    //             "order": "2"
    //         },
    //         {
    //             "latitude": 1.352083,
    //             "longitude": 103.819836,
    //             "title": "Singapore",
    //             "order": "1"
    //         },
    //         {
    //             "latitude": 10.8230989,
    //             "longitude": 106.6296638,
    //             "title": "Ho Chi Minh City",
    //             "order": "1"
    //         },
    //         {
    //             "latitude": 1.352083,
    //             "longitude": 103.819836,
    //             "title": "Singapore",
    //             "order": "2"
    //         }
    //     ]
    // ]
    var line = lineSeries.mapLines.create();
    line.id = "myline";
    line.setClassName();
    this.executeMap = function(){
        setTimeout(function(){
            // Add data for the point
            imageSeries.data = pointObject;
            lineSeries.data = [{
                "multiGeoLine": geoLine
              }];
        },2000);
    }
}