<div class="row">
    <div class="col-lg-3">
         <h1 class="p-3"> <?= $this->shipment_info[0]->shipment_num; ?></h1>    
    </div>
    <div class="col-lg-9">
        <div>
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <div class="inner">
                            <strong><i class="fas fa-check-double text-primary"></i> Status</strong>
                            <p>
                            <?php
                                $current_date = new DateTime();
                                $eta_date = new DateTime($this->shipment_info[0]->eta);
                                $current_date = $current_date->getTimestamp();
                                $eta_date = $eta_date->getTimestamp();

                                switch ($eta_date) {
                                    case ($eta_date > $current_date):
                                        echo "In Transit" . ' <i class="fas fa-shipping-fast text-primary"></i>'; // icon clock
                                        break;
                                    case (false):
                                        echo '<i class="fas fa-plane-arrival"></i> ' . "At Destination Port"; // icon marker alt
                                        break;
                                    case (false):
                                        echo '<i class="fas fa-plane-departure"></i> ' . "At Origin Port"; // icon marker
                                        break;
                                    case (false):
                                        echo '<i class="far fa-clock"></i>' . "Coordinating Departure"; // icon clock
                                        break;
                                    case ($eta_date < $current_date):
                                        echo "Delivered" . ' <i class="fas fa-check-circle text-success"></i> '; // icon check
                                        break;
                                    default:
                                        echo '<i class="fas fa-hourglass-half text-warning"></i> ' . "Pending"; // icon hour glass
                                        break;
                                }
                            ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <div class="inner">
                            <strong><i class="fas fa-history text-primary"></i> Last Updated</strong>
                            <p><?php 
                                $date = date_create($this->shipment_info[0]->trigger_date);
                                echo date_format($date,"d/m/Y H:i:s");
                            ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <div class="inner">
                            <strong><i class="fa fa-map-marker text-primary"></i> Port of Loading</strong>
                            <p><?= $this->shipment_info[0]->port_loading; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <div class="inner">
                            <strong><i class="fa fa-map-marker-alt text-primary"></i> Port of Discharge</strong>
                            <p><?= $this->shipment_info[0]->port_discharge; ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <section class="col-lg-6 connectedSortable ui-sortable">
        <div class="card">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                    <i class="fas fa-chart-pie mr-1"></i>
                    Shipment Details
                </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-4">
                    <strong>Consol ID</strong>
                    <p><?= $this->shipment_info[0]->console_id; ?></p>
                </div>
                <div class="col-lg-4">
                    <strong>House Bill</strong>
                    <p><?= $this->shipment_info[0]->house_bill; ?></p>
                </div>
                <div class="col-lg-4">
                    <strong>Master Bill</strong>
                    <p><?= $this->shipment_info[0]->master_bill; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <strong>Voyage Flight No.</strong>
                    <p><?= $this->shipment_info[0]->voyage_flight_num; ?></p>
                </div>
                <div class="col-lg-4">
                    <strong>Vessel Name</strong>
                    <p><?= $this->shipment_info[0]->vessel_name; ?></p>
                </div>
                <div class="col-lg-4">
                    <strong>Vessel Lloyds</strong>
                    <p><?= $this->shipment_info[0]->vesslloyds; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <strong>Transport Mode</strong>
                    <p><?= $this->shipment_info[0]->transport_mode; ?></p>
                </div>
                <div class="col-lg-4">
                    <strong>Estimated Departure</strong>
                    <p><?php 
                        $date = date_create($this->shipment_info[0]->etd);
                        echo date_format($date,"d F Y H:i"); ?></p>
                </div>
                <div class="col-lg-4">
                    <strong>Estimated Arrival</strong>
                    <p><?php 
                        $date = date_create($this->shipment_info[0]->eta);
                        echo date_format($date,"d F Y H:i"); ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <strong>Order Reference</strong>
                    <p><?= $this->shipment_info[0]->order_number; ?></p>
                </div>
            </div>
            <div class="collapse-control w-100 p-2 mb-2" style="background-color: #eee;" data-toggle="collapse" data-target="#consignee" aria-expanded="true">
                <h5 class="d-inline-block">Consignee</h5>
                <span class="float-right mt-2"><?= $this->shipment_info[0]->consignee; ?>
                <i class="chevron fa fa-chevron-down p-1" aria-hidden="true"></i>
                </span>
            </div>
            <dl id="consignee" class="row collapse">
                <div class="col-lg-3">
                    <dt>Code:</dt>
                    <dd><?= $this->shipment_info[0]->consignee; ?></dd>
                </div>
                <div class="col-lg-9">
                    <dt>Address:</dt>
                    <dd><?= $this->shipment_info[0]->consignee_addr; ?></dd>
                </div>
                <div class="col-lg-12">
                    <dt>Sending Agent:</dt>
                    <dd><?= $this->shipment_info[0]->sending_agent; ?></dd>
                </div>
                <div class="col-lg-12">
                    <dt>Sending Agent Address:</dt>
                    <dd><?= $this->shipment_info[0]->sending_agent_addr; ?></dd>
                </div>
                <div class="col-lg-6">
                    <dt>Delivery Place:</dt>
                    <dd><?= $this->shipment_info[0]->place_delivery; ?></dd>
                </div>
                <div class="col-lg-6">
                    <dt>Port of Loading: </dt>
                    <dd><?= $this->shipment_info[0]->port_loading; ?></dd>
                </div>
            </dl>
            <div class="collapse-control w-100 p-2 mb-2" style="background-color: #eee;" data-toggle="collapse" data-target="#consignor" aria-expanded="true" >
                <h5 class="d-inline-block">Consignor</h5>
                <span class="float-right mt-2"><?= $this->shipment_info[0]->consignor; ?>
                <i class="chevron fa fa-chevron-down p-1" aria-hidden="true"></i>
                </span>
            </div>
            <dl id="consignor" class="row collapse">
                <div class="col-lg-3">
                    <dt>Code:</dt>
                    <dd><?= $this->shipment_info[0]->consignor; ?></dd>
                </div>
                <div class="col-lg-9">
                    <dt>Address:</dt>
                    <dd><?= $this->shipment_info[0]->consignor_addr; ?></dd>
                </div>
                <div class="col-lg-12">
                    <dt>Receiving Agent:</dt>
                    <dd><?= $this->shipment_info[0]->receiving_agent; ?></dd>
                </div>
                <div class="col-lg-12">
                    <dt>Receiving Agent Address:</dt>
                    <dd><?= $this->shipment_info[0]->receiving_agent_addr; ?></dd>
                </div>
                <div class="col-lg-6">
                    <dt>Receipt Place:</dt>
                    <dd><?= $this->shipment_info[0]->place_receipt; ?></dd>
                </div>
                <div class="col-lg-6">
                    <dt>Port of Discharge: </dt>
                    <dd><?= $this->shipment_info[0]->port_discharge; ?></dd>
                </div>
            </dl>
        </div>
    </section>
    <section class="col-lg-6 connectedSortable ui-sortable">
        <div class="card">
            <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                    <i class="fas fa-map mr-1"></i>
                    Route Details
                </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div id="chartdiv" class="" style="position: relative; height: 300px;"></div>
                </div>
                <div class="col-md-12">
                <div id="accordion" class="mt-2">
                    <?php if(true):
                        $json = json_decode($this->shipment_info[0]->route_leg);
                        foreach ($json as $key => $value):
                    ?>
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                            <div class="container btn btn-link" data-toggle="collapse" data-target="#collapse<?= $value->LegOrder; ?>" aria-expanded="true" aria-controls="collapse<?= $value->LegOrder; ?>">
                                    <div class="row">
                                        <div class="col-sm-5"><?php echo $value->Origin; ?></div>
                                        <div class="col-sm-2 text-center"><i class="fas fa-arrow-right"></i></div>
                                        <div class="col-sm-5"><?php echo $value->Destination; ?></div>
                                    </div>
                                </div>
                            </h5>
                        </div>

                        <div id="collapse<?= $value->LegOrder; ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <strong>Shipment Leg </strong>
                                        <p><?= $value->LegOrder; ?> of <?= count($json) ?></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <strong>Origin Port</strong>
                                        <p><?=  $value->Origin ?></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <strong>Destination Port</strong>
                                        <p><?=  $value->Destination ?></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <strong>Vessel Name</strong>
                                        <p><?= $value->VesselName; ?></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <strong>Type</strong>
                                        <p><?= $value->LegType; ?></p>
                                    </div>
                                    <div class="col-lg-4">
                                        <strong>Status</strong>
                                        <p><?=  $value->BookingStatus ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                </div>
            </div>
            <div class="row">

            </div>
        </div>
    </section>
</div>
<?php
$transImage = "";
switch ($this->shipment_info[0]->transport_mode) {
    case 'Sea':
        $transImage = "ship";
        break;
    case 'Air':
        $transImage = "plane";
        break;    
    default:
        $transImage = "truck";
        break;
} ?>
</div>
<style>
.amcharts-myline path {
  stroke-linejoin: round;
  stroke-linecap: round;
  stroke-dasharray: 500%;
  stroke-dasharray: 0.25rem; /* fixes IE prob */
  stroke-dashoffset: 0; /* fixes IE prob */
  -webkit-animation: am-draw 100s;
  animation: am-draw 100s;
}
@-webkit-keyframes am-draw {
  0% {
    stroke-dashoffset: 500%;
  }
  100% {
    stroke-dashoffset: 0%;
  }
}
@keyframes am-draw {
  0% {
    stroke-dashoffset: 500%;
  }
  100% {
    stroke-dashoffset: 0%;
  }
}
</style>
<script src="/js/map.js"></script>
<script>
$(document).ready(function() {  
    $(".collapse").on("hidden.bs.collapse", toggleChevron);
    $(".collapse").on("shown.bs.collapse", toggleChevron);

    var route = JSON.parse(<?= json_encode($this->shipment_info[0]->route_leg) ?>);
    var combineRoute = [];
    var pointObject = [];
    var transImage = "<?= $transImage ?>";

    var tooltipHTML = `<center><strong>{vessel}</strong></center>
        <hr />
        <div class="row">
            <div class="col-lg-6">
                <table>
                    <tr>
                        <th align="left">Route</th>
                        <td>{order}</td>
                    </tr>
                    <tr>
                        <th align="left">Port: </th>
                        <td>{title}</td>
                    </tr>
                    <tr>
                        <th align="left">Point</th>
                        <td>{type}</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-6">
                <img width="200px" src="https://cargomation.com/img/vessel/{vessel}.jpg">
            </div>
        </div>
        <hr />
        <center>
            <input class="btn btn-default btn-xs mb-2" type="button" value="More info" onclick="routeBtn({order})" />
        </center>`;

    $.each(route, function(key, value) {
        combineRoute.push({
            "order": parseInt(value.LegOrder),
            "point": value.Origin,
            "vessel": value.VesselName,
            "type": "Origin",
        });
        combineRoute.push({
            "order": parseInt(value.LegOrder),
            "point": value.Destination,
            "vessel": value.VesselName,
            "type": "Destination",
        });
    });

    $.each(combineRoute, function(key, value) {
        // If point has back slash
        if(value.point.includes("/")) {
            value.point = value.point.split('/')[1];
        }
        var data = JSON.parse(getGeoData(value.point));
        if(data.status === 'OK') {
            var latitude = data.results[0].geometry.location.lat;
            var longitude = data.results[0].geometry.location.lng;
            pointObject.push({
                "latitude": latitude,
                "longitude": longitude,
                "title": value.point,
                "order": value.order,
                "vessel": value.vessel,
                "type": value.type
            });
        }
    });

    function getGeoData( location ) {
        var result = null;
        var scriptUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address='+location+'&key=AIzaSyA89i4Tuzrby4Dg-ZxnelPs-U3uvHoR9eo';
        $.ajax({
            url: scriptUrl,
            type: 'get',
            dataType: 'html',
            async: false,
            success: function(data) {
                result = data;
            } 
        });
        return result;
    }

    function toggleChevron(e) {
        $(e.target)
            .prev(".collapse-control")
            .find("i.chevron")
            .toggleClass("fa-chevron-down fa-chevron-up");
    }

    pointObject.sort((a, b) => {
        return a.order - b.order;
    });

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

        // Add line series
        var lineSeries = chart.series.push(new am4maps.MapLineSeries());
        lineSeries.mapLines.template.strokeWidth = 4;
        lineSeries.mapLines.template.stroke = am4core.color("#00ff00");
        lineSeries.mapLines.template.nonScalingStroke = true;
        // lineSeries.mapLines.template.line.strokeOpacity = 0.5;
        // lineSeries.mapLines.template.line.strokeDasharray = "3,3";

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

        // var plane = bullet.createChild(am4core.Sprite);
        var plane = bullet.createChild(am4core.Image);
        plane.href = "svgs/freight/"+transImage+".svg";
        // plane.scale = 0.15;
        plane.horizontalCenter = "middle";
        plane.verticalCenter = "middle";
        // plane.path = "";
        // plane.path = "M272 288h-64C163.8 288 128 323.8 128 368C128 376.8 135.2 384 144 384h192c8.836 0 16-7.164 16-16C352 323.8 316.2 288 272 288zM240 256c35.35 0 64-28.65 64-64s-28.65-64-64-64c-35.34 0-64 28.65-64 64S204.7 256 240 256zM496 320H480v96h16c8.836 0 16-7.164 16-16v-64C512 327.2 504.8 320 496 320zM496 64H480v96h16C504.8 160 512 152.8 512 144v-64C512 71.16 504.8 64 496 64zM496 192H480v96h16C504.8 288 512 280.8 512 272v-64C512 199.2 504.8 192 496 192zM384 0H96C60.65 0 32 28.65 32 64v384c0 35.35 28.65 64 64 64h288c35.35 0 64-28.65 64-64V64C448 28.65 419.3 0 384 0zM400 448c0 8.836-7.164 16-16 16H96c-8.836 0-16-7.164-16-16V64c0-8.838 7.164-16 16-16h288c8.836 0 16 7.162 16 16V448z";
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

        // zoomed when load
        chart.homeZoomLevel = 1.5;
        chart.homeGeoPoint = {
            latitude: pointObject[0].latitude,
            longitude: pointObject[1].longitude
        };

        chart.events.on( "ready", function(){
            animateMarker();
            imageSeries.data = pointObject;
            am4core.options.autoDispose = true;
            marker.tooltipHTML = tooltipHTML;
        });
        // setTimeout(function(){
        //     // Add data for the point
        //     imageSeries.data = pointObject;
        //     line.multiGeoLine = test;
        // },2000);

        // when marker click
        // imageSeriesTemplate.events.on("hit", (ev)=>{
        //     console.log(ev.target.dataItem.dataContext.order)
        //     var id = ev.target.dataItem.dataContext.order;
        //     $('#collapse' + id).collapse('toggle');
        // })

        // when collapse shown animate marker
        // $('.collapse').on('shown.bs.collapse', function () {
        //     animateMarker();
        // });

        // var sampleData =
        // [
        //     [
        //         {
        //             "latitude": 10.8230989,
        //             "longitude": 106.6296638,
        //             "title": "Ho Chi Minh City",
        //             "order": 1
        //         },
        //         {
        //             "latitude": 1.352083,
        //             "longitude": 103.819836,
        //             "title": "Singapore",
        //             "order": 1
        //         }
        //     ], 
        //     [
        //         {
        //             "latitude": 1.352083,
        //             "longitude": 103.819836,
        //             "title": "Singapore",
        //             "order": 2
        //         },
        //         {
        //             "latitude": -33.8688197,
        //             "longitude": 151.2092955,
        //             "title": "Sydney",
        //             "order": 2
        //         }
        //     ]
        // ];

        // function groupArrayOfObjects(list, key) {
        //     return list.reduce(function(rv, x) {
        //         (rv[x[key]] = rv[x[key]] || []).push(x);
        //         return rv;
        //     }, {});
        // };
        // var data2 = groupArrayOfObjects(pointObject,"order");
    });
});
</script>