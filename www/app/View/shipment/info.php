<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <p><b>Shipment ID: </b><span><?= $this->shipment_info[0]->shipment_num; ?></span></p>
            <p><b>House Bill: </b><span><?= $this->shipment_info[0]->house_bill; ?></span></p>
            <p><b>Transport Mode: </b><span><?= $this->shipment_info[0]->transport_mode; ?></span></p>
            <p><b>Voyage Flight No.: </b><span><?= $this->shipment_info[0]->voyage_flight_num; ?></span></p>
            <p><b>ETA: </b><span><?= $this->shipment_info[0]->eta; ?></span></p>
            <p><b>Delivery Place: </b><span><?= $this->shipment_info[0]->place_delivery; ?></span></p>
            <p><b>Consignee: </b><span><?= $this->shipment_info[0]->consignee; ?></span></p>
            <p><b>Consignee Address: </b><span><?= $this->shipment_info[0]->consignee_addr; ?></span></p>
            <p><b>Sending Agent: </b><span><?= $this->shipment_info[0]->sending_agent; ?></span></p>
            <p><b>Sending Agent Address: </b><span><?= $this->shipment_info[0]->sending_agent_addr; ?></span></p>
            <p><b>Port of Loading: </b><span><?= $this->shipment_info[0]->port_loading; ?></span></p>
        </div>
        <div class="col-md-6">
            <p><b>Console: </b><span><?= $this->shipment_info[0]->console_id; ?></span></p>
            <p><b>Master Bill: </b><span><?= $this->shipment_info[0]->master_bill; ?></span></p>
            <p><b>Vessel Name: </b><span><?= $this->shipment_info[0]->vessel_name; ?></span></p>
            <p><b>Vessel Lloyds: </b><span><?= $this->shipment_info[0]->vesslloyds; ?></span></p>
            <p><b>ETD: </b><span><?= $this->shipment_info[0]->etd; ?></span></p>
            <p><b>Receipt Place: </b><span><?= $this->shipment_info[0]->place_receipt; ?></span></p>
            <p><b>Consignor: </b><span><?= $this->shipment_info[0]->consignor; ?></span></p>
            <p><b>Consignor Address: </b><span><?= $this->shipment_info[0]->consignor_addr; ?></span></p>
            <p><b>Receiving Agent: </b><span><?= $this->shipment_info[0]->receiving_agent; ?></span></p>
            <p><b>Receiving Agent Address: </b><span><?= $this->shipment_info[0]->receiving_agent_addr; ?></span></p>
            <p><b>Port of Discharge: </b><span><?= $this->shipment_info[0]->port_discharge; ?></span></p>
        </div>
    </div>
</div>