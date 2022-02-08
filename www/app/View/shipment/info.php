

<div class="row">
    <div class="col-lg-3">
         <h1> <?= $this->shipment_info[0]->shipment_num; ?></h1>    
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
                <div class="col-lg-3">
                    <strong>Transport Mode</strong>
                    <p><?= $this->shipment_info[0]->transport_mode; ?></p>
                </div>
                <div class="col-lg-9">
                    <strong>Estimated Arrival</strong>
                    <p><?php 
                        $date = date_create( $this->shipment_info[0]->eta);
                        echo date_format($date,"F d, Y H:i:s"); ?></p>
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
                <div class="w-100 p-1" style="background-color: #eee;">
                    <h5 class="d-inline-block">Consignee</h5>
                    <span class="float-right"><?= $this->shipment_info[0]->consignee; ?></span>
                </div>
                <div class="col-lg-12">
                    <strong>Address:</strong>
                    <p><?= $this->shipment_info[0]->consignee_addr; ?></p>
                </div>
                <div class="col-lg-12">
                    <strong>Sending Agent:</strong>
                    <p><?= $this->shipment_info[0]->sending_agent; ?></p>
                </div>
                <div class="col-lg-12">
                    <strong>Sending Agent Address:</strong>
                    <p><?= $this->shipment_info[0]->sending_agent_addr; ?></p>
                </div>
                <div class="col-lg-6">
                    <strong>Delivery Place:</strong>
                    <p><?= $this->shipment_info[0]->place_delivery; ?></p>
                </div>
                <div class="col-lg-6">
                    <strong>Port of Loading: </strong>
                    <p><?= $this->shipment_info[0]->port_loading; ?></p>
                </div>
            </div>
            <div class="row">
                <div class="w-100 p-1" style="background-color: #eee;">
                    <h5 class="d-inline-block">Consignor</h5>
                    <span class="float-right"><?= $this->shipment_info[0]->consignor; ?></span>
                </div>
                <div class="col-lg-12">
                    <strong>Address:</strong>
                    <p><?= $this->shipment_info[0]->consignor_addr; ?></p>
                </div>
                <div class="col-lg-12">
                    <strong>Receiving Agent:</strong>
                    <p><?= $this->shipment_info[0]->receiving_agent; ?></p>
                </div>
                <div class="col-lg-12">
                    <strong>Receiving Agent Address:</strong>
                    <p><?= $this->shipment_info[0]->receiving_agent_addr; ?></p>
                </div>
                <div class="col-lg-6">
                    <strong>Receipt Place:</strong>
                    <p><?= $this->shipment_info[0]->place_receipt; ?></p>
                </div>
                <div class="col-lg-6">
                    <strong>Port of Discharge: </strong>
                    <p><?= $this->shipment_info[0]->port_discharge; ?></p>
                </div>
            </div>
            <div class="row">
                <p><b>ETD: </b><span><?= $this->shipment_info[0]->etd; ?></span></p>
            </div>
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
                <div class="col-md-6">
                    [INSERT MAP HERE]
                </div>
                <div class="col-md-6">
                </div>
            </div>
        </div>
    </section>
</div>

</div>