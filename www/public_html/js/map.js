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
        //chart.maxZoomLevel = 1;
        chart.seriesContainer.draggable = true;
        chart.chartContainer.wheelable = true;
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
            //animateBullet(event.target);
        })


        function animateBullet(circle) {
            var animation = circle.animate([{ property: "scale", from: 1 / chart.zoomLevel, to: 5 / chart.zoomLevel }, { property: "opacity", from: 1, to: 0 }], 1000, am4core.ease.circleOut);
            animation.events.on("animationended", function(event){
                animateBullet(event.target.object);
            })
        }

        
       // Add zoom control
        chart.zoomControl = new am4maps.ZoomControl();

        // Add button
        var button = chart.chartContainer.createChild(am4core.Button);
        button.padding(5, 5, 5, 5);
        button.align = "right";
        button.marginRight = 15;
        button.events.on("hit", function() {
        chart.goHome();
        });

        button.icon = new am4core.Sprite();
        button.icon.path = "M16,8 L14,8 L14,16 L10,16 L10,10 L6,10 L6,16 L2,16 L2,8 L0,8 L8,0 L16,8 Z M16,8";
      

        var colorSet = new am4core.ColorSet();
        var data = [];
        chart.homeZoomLevel = 1;
       
        this.executeMap = function(){
            // chart.homeGeoPoint = {
            //     latitude: pointObject[0].latitude,
            //     longitude: pointObject[0].longitude
            // };
            console.log("from map");
           console.log(pointObject);
            imageSeries.data = pointObject;
            am4core.options.autoDispose = true;
            imageSeries.mapImages.template.tooltipHTML = `{title}`
           
        }
}

function dashboardTollTip(data){
    console.log(data);
}