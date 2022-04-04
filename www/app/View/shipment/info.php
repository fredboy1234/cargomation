<div class="row">
    <div class="col-lg-3">
         <h1 class="p-3 shipment-num" data-toggle="tooltip" data-placement="bottom" title="<?= $this->shipment_info[0]->shipment_num; ?>"> <?= $this->shipment_info[0]->shipment_num; ?></h1>    
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

<div class="card card-primary card-outline card-outline-tabs">
    <div class="card-header p-0 border-bottom-0">
        <ul class="nav nav-tabs" id="custom-tabs-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tabs-info-tab" data-toggle="pill" href="#custom-tabs-info" role="tab" aria-controls="custom-tabs-info" aria-selected="true">Information</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-invoice-tab" data-toggle="pill" href="#custom-tabs-invoice" role="tab" aria-controls="custom-tabs-invoice" aria-selected="false">Invoice</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-timeline-tab" data-toggle="pill" href="#custom-tabs-timeline" role="tab" aria-controls="custom-tabs-timeline" aria-selected="false">Timeline</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-documents-tab" data-toggle="pill" href="#custom-tabs-documents" role="tab" aria-controls="custom-tabs-documents" aria-selected="false">Documents</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="custom-tabs-tabContent">
            <div class="tab-pane fade active show" id="custom-tabs-info" role="tabpanel" aria-labelledby="custom-tabs-info-tab">
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
                                    <p><?= (empty($this->shipment_info[0]->order_number)) ? '<span class="text-danger"> - </span>' : $this->shipment_info[0]->order_number; ?></p>
                                </div>
                                <div class="col-lg-4">
                                    <strong>Container Mode</strong>
                                    <p><?= $this->shipment_info[0]->container_mode; ?></p>
                                </div>
                            </div>
                            <div class="collapse-control w-100 p-2 mb-2" style="background-color: #eee;" data-toggle="collapse" data-target="#consignee" aria-expanded="true">
                                <h5 class="d-inline-block">Consignee</h5>
                                <span class="float-right mt-2"><?= $this->shipment_info[0]->consignee; ?>
                                <i class="chevron fa fa-chevron-down p-1" aria-hidden="true"></i>
                                </span>
                            </div>
                            <dl id="consignee" class="row collapse">
                                <?php if(false): ?>
                                <div class="col-lg-12">
                                    <dt>Sending Agent:</dt>
                                    <dd><?= $this->shipment_info[0]->sending_agent; ?></dd>
                                </div>
                                <div class="col-lg-12">
                                    <dt>Sending Agent Address:</dt>
                                    <dd><?= $this->shipment_info[0]->sending_agent_addr; ?></dd>
                                </div>
                                <div class="col-lg-6">
                                    <dt>Company Name:</dt>
                                    <dd><?=  (empty($this->shipment_contact[0]->company_name)) ? '<span class="text-danger"> - </span>' : $this->shipment_contact[0]->company_name; ?></dd>
                                </div>
                                <div class="col-lg-6">
                                    <dt>Organization Code:</dt>
                                    <dd><?= $this->shipment_info[0]->consignee; ?></dd>
                                </div>
                                <div class="col-lg-12">
                                    <dt>Address:</dt>
                                    <dd><?= $this->shipment_info[0]->consignee_addr; ?></dd>
                                </div>
                                <?php endif; ?>
                                <?php 
                                $json_org = json_decode($this->shipment_info[0]->organization);
                                if(!empty($this->shipment_info[0]->organization)) {
                                    foreach ($json_org as $key => $value) {
                                        if ($value->AddressType == "ConsigneePickupDeliveryAddress") {
                                ?>
                                <div class="col-lg-12">
                                    <dt>Company Name:</dt>
                                    <dd> <?= $value->CompanyName; ?> </dd>
                                </div>
                                <div class="col-lg-6">
                                    <dt>Organization Code:</dt>
                                    <dd> <?= $value->OrganizationCode; ?> </dd>
                                </div>
                                <div class="col-lg-6">
                                    <dt>Organization Name:</dt>
                                    <dd><?= (empty($this->shipment_contact[0]->company_name)) ? '<span class="text-danger"> - </span>' : $this->shipment_contact[0]->company_name; ?></dd>
                                </div>
                                <div class="col-lg-6">
                                    <dt>Delivery Address:</dt>
                                    <dd> <?= $value->Address1; ?> </dd>
                                </div>
                                <?php 
                                        }
                                    }
                                }
                                ?>
                                <div class="col-lg-6">
                                    <dt>Delivery Date:</dt>
                                    <dd>
                                    <?php if(empty($this->container_detail)): ?>   
                                    <span> - </span>
                                    <?php else: ?>  
                                    <span><?= date("d F Y H:i", strtotime($this->container_detail[0]->trans_estimated_delivery)); ?></span>
                                    <?php endif; ?>
                                    <?php 
                                        // $date = date_create($this->shipment_info[0]->etd);
                                        // echo date_format($date,"d F Y H:i"); 
                                    ?>
                                    </dd>
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
                                <div class="col-lg-12">
                                    <dt>Organization Code:</dt>
                                    <dd><?= $this->shipment_info[0]->consignor; ?></dd>
                                </div>
                                <div class="col-lg-12">
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
                
                            <div class="collapse-control w-100 p-2 mb-2" style="background-color: #eee;" data-toggle="collapse" data-target="#container_details" aria-expanded="true" >
                                <h5 class="d-inline-block">Container Details</h5>
                                <span class="float-right mt-2"><?= $this->shipment_info[0]->shipment_num; ?>
                                <i class="chevron fa fa-chevron-down p-1" aria-hidden="true"></i>
                                </span>
                            </div>
                            <div id="container_details" class="collapse">  
                            <?php if(empty($this->container_detail)): ?>   
                                <span> No Container Data </span>
                            <?php else: ?>
                                <?php foreach ($this->container_detail as $key => $value): $value->no_data = '<span class="text-danger"> - </span>'; ?>
                                    <div class="collapse-control w-100 p-2 mb-2" style="background-color: #cdcdcd;" data-toggle="collapse" data-target="#cd-<?= $value->id ?>" aria-expanded="true" >
                                        <span class="d-inline-block"><?= $value->containernumber; ?>
                                            <?php if(false): ?>
                                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
                                            <i class='fas fa-skull-crossbones' aria-hidden="true"></i>
                                            <?php endif; ?>
                                        </span>
                                        <span class="float-right mt-2">
                                            <i class="chevron fa fa-chevron-down p-1" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                    <dl id="cd-<?= $value->id ?>" class="row collapse">
                                        <div class="col-lg-3">
                                            <dt>Container Type:</dt>
                                            <dd><?= $value->containertype; ?></dd>
                                        </div>
                                        <div class="col-lg-3">
                                            <dt>Delivery Mode:</dt>
                                            <dd><?= $value->containerdeliverymode; ?></dd>
                                        </div>
                                        <div class="col-lg-3">
                                            <dt>Gross Wt.:</dt>
                                            <dd><?= $value->no_data; ?></dd>
                                        </div>
                                        <div class="col-lg-3">
                                            <dt>Volume:</dt>
                                            <dd><?php
                                            $length = floatval($value->length);
                                            $width = floatval($value->width);
                                            $height = floatval($value->height); 
                                            $volume = $length * $width * $height;
                                            echo number_format($volume, 2, '.', ',') . "cm³"; ?></dd>
                                        </div>
                                        <div class="col-lg-3">
                                            <dt>Packs:</dt>
                                            <dd><?= $value->no_data; ?></dd>
                                        </div>
                                        <div class="col-lg-3">
                                            <dt>Gate In: </dt>
                                            <dd><?= $value->no_data; ?></dd>
                                        </div>
                                        <div class="col-lg-3">
                                            <dt>FCL Available: </dt>
                                            <dd><?= $value->no_data; ?></dd>
                                        </div>
                                        <div class="col-lg-3">
                                            <dt>FCL Loaded In: </dt>
                                            <dd><?= $value->no_data; ?></dd>
                                        </div>
                                        <div class="col-lg-3">
                                            <dt>Storage Date:</dt>
                                            <dd><?= $value->no_data; ?></dd>
                                        </div>
                                        <div class="col-lg-3">
                                            <dt>Empty Req. By:</dt>
                                            <dd><?= $value->no_data; ?></dd>
                                        </div>
                                    </dl>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </div>
                        </div>
                    </section>
                    <section class="col-lg-6 connectedSortable ui-sortable">
                        <div class="card">
                        <div class="card card-primary card-outline card-tabs">
                            <div class="card-header p-0 pt-1 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="false"><i class="fas fa-map mr-1"></i>
                                        Route Details</a>
                                    </li>
                                    <?php if(!empty($this->shipment_info[0]->vesslloyds)) : ?>
                                    <li class="nav-item">
                                        <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false"><i class="fas fa-bullseye mr-1"></i>
                                        GPS Tracking</a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body">
                                <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
                                    <div id="chartdiv" class="" style="position: relative; height: 300px;">
                                        <div class="spinner-border" role="status" style="position: absolute;bottom: 50%;right: 50%;">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                                    <div style="background-color:white;position:relative;top:-15px;z-index: 1">&nbsp;</div>
                                    <?php if(!empty($this->shipment_info[0]->vesslloyds)) : ?>
                                    <iframe style="position: relative;top: -35px;" name="vesselfinder" id="vesselfinder" src="https://www.vesselfinder.com/aismap?zoom=undefined&amp;lat=undefined&amp;lon=undefined&amp;width=100%&amp;height=500&amp;names=false&amp;imo=<?php echo $this->shipment_info[0]->vesslloyds;?>&amp;track=false&amp;fleet=false&amp;fleet_name=false&amp;fleet_hide_old_positions=false&amp;clicktoact=false&amp;store_pos=false&amp;ra=livetracking_" width="100%" height="352" frameborder="0"></iframe>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div id="accordion" class="mt-2">
                                        <?php if($this->shipment_info[0]->route_leg !== "[]"):
                                            $json = json_decode($this->shipment_info[0]->route_leg); ?>
                                        <?php foreach ($json as $key => $value): ?>
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
                                                            <strong>Leg Type</strong>
                                                            <p><?= $value->LegType; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <strong>Vessel Name</strong>
                                                            <p><?= $value->VesselName; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <strong>Origin Port</strong>
                                                            <p><?=  $value->Origin ?></p>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <strong>Destination Port</strong>
                                                            <p><?=  $value->Destination ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <?php if(isset($value->ETD)): ?>
                                                        <div class="col-lg-6">
                                                            <strong>Estimated Departure </strong>
                                                            <p><?= $value->ETD; ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if(isset($value->ETA)): ?>
                                                        <div class="col-lg-6">
                                                            <strong>Estimated Arrival</strong>
                                                            <p><?= $value->ETA; ?></p>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4">
                                                            <strong>Carrier Org</strong>
                                                            <p><?= (!empty($value->CarrierOrg))?$value->CarrierOrg:" - "; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <strong>Carrier Name</strong>
                                                            <p><?= (!empty($value->CarrierName))?$value->CarrierName:" - "; ?></p>
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <strong>Address Type</strong>
                                                            <p><?= (!empty($value->AddressType))?$value->AddressType:" - "; ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <strong>Status</strong>
                                                            <p class="<?= (!empty($value->BookingStatus))?$value->BookingStatus:" - "; ?>">
                                                            <?= (!empty($value->BookingDesc))?$value->BookingDesc:" - "; ?> 
                                                            (<?= (!empty($value->BookingStatus))?$value->BookingStatus:" - "; ?>)
                                                        </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                            <span>No route data</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>
                </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-invoice" role="tabpanel" aria-labelledby="custom-tabs-invoice-tab">
                <div class="row">
                    <section class="col-lg-12 connectedSortable ui-sortable">
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                    <i class="fas fa-file-invoice"></i>
                                    Invoice Details
                                </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-0 ttable" style="height: auto;">
                            <table id="example" class="table table-hover table-head-fixed text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Account</th>
                                        <th>Type</th>
                                        <th>Trans.#</th>
                                        <th>Job Inv.#</th>
                                        <th>Post Date</th>
                                        <th>Invoice Date</th>
                                        <th>Fully Paid Date</th>
                                        <th>Payment Status</th>
                                        <th>Invoice Amount</th>
                                        <th>Currency</th>
                                        <th>Outstanding Bal</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>Account</th>
                                        <th>Type</th>
                                        <th>Trans.#</th>
                                        <th>Job Inv.#</th>
                                        <th>Post Date</th>
                                        <th>Invoice Date</th>
                                        <th>Fully Paid Date</th>
                                        <th>Payment Status</th>
                                        <th>Invoice Amount</th>
                                        <th>Currency</th>
                                        <th>Outstanding Bal</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </section>
                </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-timeline" role="tabpanel" aria-labelledby="custom-tabs-timeline-tab">
            Comming soon...
            </div>
            <div class="tab-pane fade" id="custom-tabs-documents" role="tabpanel" aria-labelledby="custom-tabs-documents-tab">
                <table id="doc_table" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Date Received</th>
                            <th>Comments</th>
                        </tr>
                    </thead>
                </table>
                <canvas id="donutChart" style="display:none; min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                <div class="container-fluid">
                    <?php if(false): ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card mt-4">
                                <div class="card-header">Document Status</div>
                                <div class="card-body">
                                    <div class="chart-container pie-chart">
                                        <canvas id="pie_chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mt-4">
                                <div class="card-header">Document Request</div>
                                <div class="card-body">
                                    <div class="chart-container pie-chart">
                                        <canvas id="doughnut_chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card mt-4 mb-4">
                                <div class="card-header">Comments</div>
                                <div class="card-body">
                                    <div class="chart-container pie-chart">
                                        <canvas id="bar_chart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</div>
<?php
$transImage = "";
$transMode = $this->shipment_info[0]->transport_mode;
switch ($transMode) {
    case 'Sea':
        $transImage = "M1320 5720 l0 -320 -301 0 c-316 0 -329 -2 -329 -45 0 -22 -281 -679 -292 -683 -4 -2 -8 -12 -8 -22 0 -10 -4 -20 -10 -22 -5 -1 -14 -23 -19 -48 -5 -25 -37 -120 -70 -212 -119 -327 -164 -544 -164 -798 0 -256 36 -436 159 -795 41 -121 78 -232 81 -248 3 -15 9 -27 14 -27 5 0 9 -10 9 -22 0 -13 7 -36 16 -53 17 -33 261 -631 278 -682 6 -18 14 -33 18 -33 5 0 8 -7 8 -15 0 -13 41 -15 305 -15 l305 0 0 -290 0 -290 405 0 405 0 0 290 0 290 3510 0 3510 0 29 28 c16 15 161 103 323 196 623 361 1044 620 1308 806 67 47 142 100 168 117 26 18 79 61 117 95 197 176 309 313 355 435 28 72 37 185 20 261 -28 132 -149 307 -313 454 -50 45 -99 90 -108 100 -9 10 -19 18 -22 18 -3 0 -63 44 -133 97 -245 184 -646 442 -1289 828 -209 126 -384 235 -388 242 -4 7 -16 13 -27 13 -10 0 -20 6 -22 13 -4 9 -719 13 -3521 15 l-3517 2 0 320 0 320 -405 0 -405 0 0 -320z m6760 -1005 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-5140 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m400 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m380 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m2470 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m450 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m1830 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-2740 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m1460 -230 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m390 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-2430 -80 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-860 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m430 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m5007 -141 c151 -31 260 -90 368 -199 283 -286 231 -746 -110 -974 -351 -234 -843 -112 -1025 255 -66 132 -85 268 -56 404 76 357 451 591 823 514z m-1647 -279 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-4340 -20 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m4760 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-5590 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m420 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m3330 -40 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-980 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m510 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m1420 -420 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-2470 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m2070 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-2510 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-440 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-530 -320 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-410 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-430 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m5180 -160 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m400 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-1800 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-1000 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m480 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-970 -290 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-500 -20 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-450 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m2940 -110 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m410 0 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-3850 -160 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-410 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205z m-450 -10 l0 -205 -125 0 -125 0 0 205 0 205 125 0 125 0 0 -205zM9435 3788 c-3 -8 -4 -97 -3 -198 l3 -185 40 0 40 0 3 83 3 82 74 0 75 0 0 -86 0 -85 38 3 37 3 0 195 0 195 -37 3 -38 3 0 -80 0 -81 -75 0 -74 0 -3 78 -3 77 -38 3 c-24 2 -39 -1 -42 -10z";
        break;
    case 'Air':
        $transImage = "m2,106h28l24,30h72l-44,-133h35l80,132h98c21,0 21,34 0,34l-98,0 -80,134h-35l43,-133h-71l-24,30h-28l15,-47";
        break;    
    default:
        $transImage = "M34.533,390.596c-10.214,8.754-17.231,21.053-19.316,35.02H6.949c-3.822,0-6.949-3.127-6.949-6.949v-21.123   c0-3.822,3.126-6.947,6.949-6.947H34.533L34.533,390.596z M114.159,425.615c0.556,2.709,0.834,5.418,0.834,8.268   c0,23.832-19.316,43.217-43.218,43.217c-23.832,0-43.218-19.385-43.218-43.217c0-2.85,0.277-5.559,0.833-8.268   c3.821-19.943,21.331-35.02,42.384-35.02S110.407,405.672,114.159,425.615z M93.384,433.883c0-2.918-0.555-5.768-1.667-8.268   c-3.196-7.852-10.909-13.342-19.941-13.342c-9.033,0-16.745,5.49-19.942,13.342c-1.112,2.5-1.667,5.35-1.667,8.268   c0,11.881,9.658,21.609,21.609,21.609C83.727,455.492,93.384,445.764,93.384,433.883z M17.106,362.873V155.745   c0-11.512,9.333-20.845,20.845-20.845h332.125c11.513,0,20.845,9.333,20.845,20.845v207.128c0,3.82-3.126,6.947-6.948,6.947H24.055   C20.233,369.82,17.106,366.693,17.106,362.873z M340.476,339.488c0,3.299,2.674,5.973,5.973,5.973h0.186   c3.298,0,5.972-2.674,5.972-5.973V167.625c0-3.298-2.674-5.972-5.972-5.972h-0.186c-3.299,0-5.973,2.674-5.973,5.972V339.488z    M284.314,339.488c0,3.299,2.673,5.973,5.971,5.973h0.186c3.299,0,5.972-2.674,5.972-5.973V167.625   c0-3.298-2.673-5.972-5.972-5.972h-0.186c-3.298,0-5.971,2.674-5.971,5.972V339.488z M228.151,339.488   c0,3.299,2.674,5.973,5.972,5.973h0.186c3.298,0,5.972-2.674,5.972-5.973V167.625c0-3.298-2.674-5.972-5.972-5.972h-0.186   c-3.298,0-5.972,2.674-5.972,5.972V339.488z M171.99,339.488c0,3.299,2.674,5.973,5.972,5.973h0.186   c3.298,0,5.972-2.674,5.972-5.973V167.625c0-3.298-2.674-5.972-5.972-5.972h-0.186c-3.298,0-5.972,2.674-5.972,5.972V339.488z    M115.828,339.488c0,3.299,2.673,5.973,5.972,5.973h0.186c3.298,0,5.971-2.674,5.971-5.973V167.625   c0-3.298-2.673-5.972-5.971-5.972H121.8c-3.299,0-5.972,2.674-5.972,5.972V339.488z M59.666,339.488   c0,3.299,2.674,5.973,5.972,5.973h0.186c3.299,0,5.972-2.674,5.972-5.973V167.625c0-3.298-2.673-5.972-5.972-5.972h-0.186   c-3.298,0-5.972,2.674-5.972,5.972V339.488z M220.815,425.615c0.556,2.709,0.834,5.418,0.834,8.268   c0,23.832-19.316,43.217-43.218,43.217c-23.833,0-43.218-19.385-43.218-43.217c0-2.85,0.278-5.559,0.833-8.268   c3.822-19.943,21.332-35.02,42.385-35.02S217.063,405.672,220.815,425.615z M200.04,433.883c0-2.918-0.556-5.768-1.598-8.268   c-3.266-7.852-10.979-13.342-20.011-13.342s-16.746,5.49-20.011,13.342c-1.042,2.5-1.598,5.35-1.598,8.268   c0,11.881,9.658,21.609,21.61,21.609C190.382,455.492,200.04,445.764,200.04,433.883z M141.188,390.596h-32.169   c7.087,6.113,12.715,13.965,16.12,22.859C128.473,404.562,134.101,396.709,141.188,390.596z M612,397.543v21.123   c0,3.822-3.127,6.949-6.948,6.949h-56.28c-4.03-27.586-27.724-48.916-56.42-48.916s-52.459,21.33-56.489,48.916H234.989   c-2.084-13.967-9.102-26.266-19.316-35.02H410.78V191.876c0-7.644,6.183-13.897,13.896-13.897h65.592   c18.482,0,35.714,9.172,46.065,24.458l42.315,62.673c6.184,9.172,9.519,20.011,9.519,31.058v94.427h16.884   C608.873,390.596,612,393.721,612,397.543z M541.545,266.708l-33.838-48.082c-1.32-1.806-3.404-2.918-5.697-2.918h-52.736   c-3.821,0-6.949,3.126-6.949,6.949v48.012c0,3.891,3.128,6.948,6.949,6.948h86.574   C541.477,277.617,544.741,271.294,541.545,266.708z M535.569,433.883c0,23.832-19.385,43.217-43.218,43.217   c-23.902,0-43.218-19.385-43.218-43.217c0-23.902,19.316-43.287,43.218-43.287C516.185,390.596,535.569,409.98,535.569,433.883z    M513.961,433.883c0-11.951-9.658-21.609-21.609-21.609s-21.609,9.658-21.609,21.609c0,11.881,9.658,21.609,21.609,21.609   S513.961,445.764,513.961,433.883z";
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
/* Data Tables */
table.dataTable {
    margin-top: 0 !important;
}
table.dataTable>tbody>tr.child ul.dtr-details {
    width: 100%;
}
#DataTables_Table_0_processing{
    position: fixed;
    top: 50%;
    left: 50%;
    z-index: 1000;
    display: block;
    background: transparent;
    border: 0;
    border-radius: 0;
    box-shadow: none;
}
.dataTables_empty{
    text-align: center;
}
.dataTables_filter{
    display: none !important;
}
.dataTables_wrapper {
    position: relative;
}
.dataTables_info {
    padding-left: 1em;
}
.dataTables_paginate {
    padding-right: 1em;
}
#example_length {
    display: none;
}
#chartdiv > div > svg > g > g:nth-child(2) > g:nth-child(1) > g:nth-child(2) > g:nth-child(1) > g:nth-child(2) > g:nth-child(1) > g > g:nth-child(6) > g > g:nth-child(3) > g > g:nth-child(4) > g > g{
    transform: rotate(-90deg);
}
.shipment-num {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
<script src="/js/map.js"></script>
<script>
var cnt = 0;
var stoper = 0;
var keycount = 0;
var promises = [];
var route = JSON.parse(<?= json_encode($this->shipment_info[0]->route_leg) ?>);
var combineRoute = [];
var pointObject = [];
var transImage = "<?= $transImage ?>";
var transmode = "<?=$transMode?>";
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
            <img width="200px" src="https://cargomation.com/img/vessel/{vessel}.jpg" onerror="this.onerror=null;this.src='https://cargomation.com/img/vessel/noimg.png';">
        </div>
    </div>
    <hr />
    <center>
        <input class="btn btn-default btn-xs mb-2" type="button" value="More info" onclick="routeBtn({order})" />
    </center>`;

$(document).ready(function() { 
    $(".collapse").on("hidden.bs.collapse", toggleChevron);
    $(".collapse").on("shown.bs.collapse", toggleChevron);
    $.each(route, function(key, value) {
        combineRoute.push({
            "order": parseInt(value.LegOrder),
            "point": value.Origin,
            "vessel": value.VesselName,
            "type": "Origin",
            "keycount":keycount++
        });
        combineRoute.push({
            "order": parseInt(value.LegOrder),
            "point": value.Destination,
            "vessel": value.VesselName,
            "type": "Destination",
            "keycount":keycount++
        });
    });
    $.each(combineRoute, function(key, value) {
            // If point has back slash
            if(value.point.includes("/")) {
                value.point = value.point.split('/')[1];
            }
            var data = null;
            var promise  = $.ajax({
                url: document.location.origin + '/shipment/getCity/',
                type: "POST",
                dataType: "json",
                data: { location: value.point },
                success: function (res) {
                    console.log(res);
                    data = res;
                    if(data.length > 0) {
                        var latitude = data[0].lat
                        var longitude = data[0].lng;
    
                        pointObject.push({
                            "latitude": parseFloat(latitude),
                            "longitude":parseFloat( longitude),
                            "title": value.point,
                            "order": value.order,
                            "vessel": value.vessel,
                            "type": value.type,
                            "keycount":value.keycount
                        });
                        cnt++;
                    }
                }
            }); 
            promises.push(promise);
        });

        $.when.apply($, promises).done(function() {
            pointObject.sort((a, b) => { return a.keycount - b.keycount;});
            if(transmode === "Sea"){
                $.getScript("/js/shipment/sea.map.js", function() {}); 
            }else{ 
                $.getScript("/js/shipment/air.map.js", function() {}); 
            }
        }).fail(function() {
            console.log("fail");
        });
    

    $('#example').DataTable( { 
        responsive: true,
        ajax: "/uploads/sample.json"
    });
    $('.shipment-num').tooltip();
    // makechart();
	function makechart() {
		$.ajax({
			url:"document/getDocumentData/<?= $this->shipment_info[0]->shipment_num; ?>",
			method:"POST",
			data:{action:'fetch', column:'type'},
			dataType:"JSON",
			success:function(data) {
				var type = [];
				var total = [];
				var color = [];

				for(var count = 0; count < data.length; count++) {
					type.push(data[count].type);
					total.push(data[count].total);
					color.push(data[count].color);
                    // color.push('#' + Math.floor(Math.random()*16777215).toString(16));
				}

				var donutData = {
					labels:type,
					datasets:[
						{
							label:'Document',
							backgroundColor:color,
							color:'#fff',
							data:total
						}
					]
				};

                var donutChartCanvas = $('#donutChart').get(0).getContext('2d')

                var donutOptions     = {
                    maintainAspectRatio : false,
                    responsive : true,
                }

                new Chart(donutChartCanvas, {
                    type: 'doughnut',
                    data: donutData,
                    options: donutOptions
                });


			}
		})
	}
    function toggleChevron(e) {
        $(e.target)
            .prev(".collapse-control")
            .find("i.chevron")
            .toggleClass("fa-chevron-down fa-chevron-up");
    }

    //  $("#custom-tabs-info-tab").on('click',function(){
    //     if(transmode === "Sea"){
    //             $.getScript("/js/shipment/sea.map.js", function() {}); 
    //         }else{ 
    //             $.getScript("/js/shipment/air.map.js", function() {}); 
    //         }
    //  });
 
    $('#doc_table').DataTable( {
        processing: true,
        language: {
            processing: '<center><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span></center>'
        },
        serverSide: true,
        serverMethod: 'post',
        ajax: {
            url: 'document/getDocumentData/<?= $this->shipment_info[0]->shipment_num; ?>',
        },
        columns: [
            { data: 'type', defaultContent: "OTHER" },
            { data: 'status', defaultContent: "-" },
            { data: 'saved_date', defaultContent: "-" },
            { data: 'message', defaultContent: "-" }
        ]
    } );
});

$(document).ready(function() {
    $('#example').DataTable( { 
        responsive: true,
        ajax: "/uploads/sample.json"
    } );
})
</script>
