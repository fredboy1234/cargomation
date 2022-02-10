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
                            <strong>Status</strong>
                            <p>In Transit</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <div class="inner">
                            <strong>Last Updated</strong>
                            <p>A day ago</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <div class="inner">
                            <strong>Port of Loading</strong>
                            <p><?= $this->shipment_info[0]->port_loading; ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="small-box">
                        <div class="inner">
                            <strong>Port of Discharge</strong>
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
                    <strong>Console ID</strong>
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
                    <strong>Voyage Flight No.:</strong>
                    <p><?= $this->shipment_info[0]->voyage_flight_num; ?></p>
                </div>
                <div class="col-lg-4">
                    <strong>Vessel Name:</strong>
                    <p><?= $this->shipment_info[0]->vessel_name; ?></p>
                </div>
                <div class="col-lg-4">
                    <strong>Vessel Lloyds:</strong>
                    <p><?= $this->shipment_info[0]->vesslloyds; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <strong>Transport Mode</strong>
                    <p><?= $this->shipment_info[0]->transport_mode; ?></p>
                </div>
                <div class="col-lg-3">
                    <strong>Departured</strong>
                    <p><?php 
                        $date = date_create( $this->shipment_info[0]->etd);
                        echo date_format($date,"d/m/Y"); ?></p>
                </div>
                <div class="col-lg-5">
                    <strong>Estimated Arrival</strong>
                    <p><?php 
                        $date = date_create( $this->shipment_info[0]->eta);
                        echo date_format($date,"F d, Y H:i:s"); ?></p>
                </div>
            </div>
            <div class="w-100 p-2 mb-2" style="background-color: #eee;" data-toggle="collapse" data-target="#consignee" aria-expanded="true">
                <h5 class="d-inline-block">Consignee</h5>
                <span class="float-right"><?= $this->shipment_info[0]->consignee; ?></span>
            </div>
            <dl id="consignee" class="row collapse show">
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
            <div class="w-100 p-2 mb-2" style="background-color: #eee;" data-toggle="collapse" data-target="#consignor" aria-expanded="true" >
                <h5 class="d-inline-block">Consignor</h5>
                <span class="float-right"><?= $this->shipment_info[0]->consignor; ?></span>
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
var route = JSON.parse(<?= json_encode($this->shipment_info[0]->route_leg) ?>);
$(document).ready(function() {  

    var pointLeg = [];
    var pointObject = [];
    var newObject = [];

    $.each(route, function(key, value) {
        pointLeg.push({
            "order": value.LegOrder,
            "point": value.Origin
        });
        pointLeg.push({
            "order": value.LegOrder,
            "point": value.Destination
        });
    });

    $.each(pointLeg, function(key, value) {
        $.get('https://maps.googleapis.com/maps/api/geocode/json?address='+value.point+'&key=AIzaSyA89i4Tuzrby4Dg-ZxnelPs-U3uvHoR9eo', function(data){ 
            if(data.status === 'OK') {
                var latitude = data.results[0].geometry.location.lat;
                var longitude = data.results[0].geometry.location.lng;
                pointObject.push({
                    "latitude": latitude,
                    "longitude": longitude,
                    "title": value.point,
                    "order": value.order
                });
            }
        });
    });

    console.log(pointObject);

    let map = new simpleMap(pointObject, "chartdiv");
    map.executeMap();
});
</script>