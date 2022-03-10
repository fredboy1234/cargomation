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
        // imageSeries.mapImages.template.tooltipText = "{title}";
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

        // var imageSeriesTemplate = imageSeries.mapImages.template;
        // var marker = imageSeriesTemplate.createChild(am4core.Image);
        // marker.href = "https://s3-us-west-2.amazonaws.com/s.cdpn.io/t-160/marker.svg";
        // marker.width = 20;
        // marker.height = 20;
        // marker.color = "#333";
        // marker.fill = am4core.color("#007bff");
        // marker.nonScaling = true; 
        
        
        // marker.horizontalCenter = "middle";
        // marker.verticalCenter = "bottom";


        var colorSet = new am4core.ColorSet();
        var data = [];
        chart.homeZoomLevel = 1;
       
        this.executeMap = function(){
            // chart.homeGeoPoint = {
            //     latitude: pointObject[0].latitude,
            //     longitude: pointObject[0].longitude
            // };
            console.log(pointObject);
            console.log(imageSeries.mapImages.template);
            
            imageSeries.data = pointObject;
            am4core.options.autoDispose = true;
            imageSeries.mapImages.template.tooltipHTML = dashboardTollTip(pointObject);
        }
}

function dashboardTollTip(data){

    return `{title}`;
}