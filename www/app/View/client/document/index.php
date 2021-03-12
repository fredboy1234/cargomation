<?= $this->getCSS(); ?>
<?php 
$approved = 0;
$pending = 0;
$freview = 0;
$status_icon = "fa-thumbs-down";
$document_settings = json_decode($this->user_settings[0]->document);
if(!empty($this->document)) {
    foreach ($this->document as $key => $file) {
        if($file->status !== 'deleted') {
            // $initialPreview[] = "<object data='data:application/pdf;base64," . $file->img_data . "' type='text/plain' class='' alt='" . $file->name . "' ><div class='file - preview - other'> <span class='file - icon - 4x'><i class='glyphicon glyphicon-file'></i></span> </div></object>";
            // $initialPreview[] = base$file->img_data;
            // $path = "E:/A2BFREIGHT_MANAGER/CLIENT_$id/CW_FILE/$shipkey/$pathName/";
            if(false) {
                $server_file = '/filemanager/' . $this->email . '/CW_FILE/' . $file->shipment_num . '/' . $file->type . '/' . $file->name;
            }

            $server_file = "/document/fileviewer/" . $this->id . "/"  . $file->document_id;


            if($file->status == 'approved') {
                $approved++;
                $status_icon = "fa-thumbs-up";
            }
            if($file->status == 'pending') {
                $pending++;
                $status_icon = "fa-thumbs-down";
            }
            if($file->status == 'review') {
                $freview++;
                $status_icon = "fa-search";
            }

            $initialPreview[] = $server_file;
            $initialPreviewConfig[] = [ 'caption' => strlen($file->name) > 20 ? substr($file->name,0,20)."..." : $file->name,
                                        'width' => '200px',
                                        'type' => 'pdf',
                                        'source' => $file->upload_src,
                                        'size' => " ",
                                        'previewAsData' => true,
                                        'frameClass' => 'd-' . $file->document_id . ' b-' . $file->status,
                                        'key' => $file->document_id,
                                        'dataKey' => $file->document_id,
                                        'dataUrl' => $file->document_id,
                                        'extra' => ['status' => $file->status]];
            $initialPreviewThumbTags[] = ['{status}' => $file->status,
                                        '{origin}' => $file->upload_src,
                                            '{icon}' => $status_icon,
                                            '{date}' => $file->saved_date,
                                            '{type}' => $file->type,
                                            '{id}' => $file->document_id];
        }
    }
} ?>

<div id="document-upload" style="display: block;">
    <div class="row">
        <div class="col-md-5">
            <h5><b>Shipment ID: </b><?= $this->shipment_info[0]->shipment_num; ?></h5>
        </div>
        <div class="col-md-5">
            <!-- <h5><b>Status: </b>
                <span class="text-success">Approved (<?= $approved; ?>)</span>
                <span class="text-danger"> Pending (<?= $pending; ?>) </span>
                <span class="text-warning">For Review (<?= $freview; ?>)</span> 
            </h5> -->
            <h5><b>Type: </b><?= !empty($this->shipment['type'])? $this->shipment['type'] : "ALL"; ?></h5>
        </div>
        <div class="col-md-2">
            <h5><b>Total: </b><?= count($this->document); ?></h5>
        </div>
    </div>

    <div class="card card-outline card-primary collapsed-card" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
        <div class="card-header">
            <h3 class="card-title">Shipment Info</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="card-refresh" data-source="/shipment/document/<?= $this->shipment['shipment_id'] . '/' . $this->shipment['type'] ?>" data-source-selector="#card-refresh-content" data-load-on-init="false">
                <i class="fas fa-sync-alt"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="maximize">
                <i class="fas fa-expand"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="display: none;">
            <div class="row">
                <div class="col-md-6">
                    <p><b>Shipment ID: </b><?= $this->shipment_info[0]->shipment_num; ?></p>
                    <p><b>House Bill: </b><?= $this->shipment_info[0]->house_bill; ?></p>
                    <p><b>Transport Mode: </b><?= $this->shipment_info[0]->transport_mode; ?></p>
                    <p><b>Voyage Flight No.: </b><?= $this->shipment_info[0]->voyage_flight_num; ?></p>
                    <p><b>ETA: </b><?= $this->shipment_info[0]->eta; ?></p>
                    <p><b>Delivery Place: </b><?= $this->shipment_info[0]->place_delivery; ?></p>
                    <p><b>Consignee: </b><?= $this->shipment_info[0]->consignee; ?></p>
                    <p><b>Consignee Address: </b><?= $this->shipment_info[0]->consignee_addr; ?></p>
                    <p><b>Sending Agent: </b><?= $this->shipment_info[0]->sending_agent; ?></p>
                    <p><b>Sending Agent Address: </b><?= $this->shipment_info[0]->sending_agent_addr; ?></p>
                </div>
                <div class="col-md-6">
                    <p><b>Console: </b><?= $this->shipment_info[0]->console_id; ?></p>
                    <p><b>Master Bill: </b><?= $this->shipment_info[0]->master_bill; ?></p>
                    <p><b>Vessel Name: </b><?= $this->shipment_info[0]->vessel_name; ?></p>
                    <p><b>Vessel Lloyds: </b><?= $this->shipment_info[0]->vesslloyds; ?></p>
                    <p><b>ETD: </b><?= $this->shipment_info[0]->etd; ?></p>
                    <p><b>Receipt Place: </b><?= $this->shipment_info[0]->place_receipt; ?></p>
                    <p><b>Consignor: </b><?= $this->shipment_info[0]->consignor; ?></p>
                    <p><b>Consignor Address: </b><?= $this->shipment_info[0]->consignor_addr; ?></p>
                    <p><b>Receiving Agent: </b><?= $this->shipment_info[0]->receiving_agent; ?></p>
                    <p><b>Receiving Agent Address: </b><?= $this->shipment_info[0]->receiving_agent_addr; ?></p>
                </div>
            </div>
        </div>
        <!-- /.card-body -->
    </div>

    <div class="file-loading" style="display: none;">
        <input id="input" name="input[]" type="file" accept="application/*" multiple>
    </div>
    <div id="kv-error-1" style="margin-top:10px; display:none"></div>
    <div id="kv-success-1" class="alert alert-success" style="margin-top:10px; display:none"></div>
</div>
<div id="document-comment" style="display: none;">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Comment</h3>

            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="form-group">
            <label for="inputName">Title</label>
            <input type="text" id="inputName" class="form-control">
            </div>
            <div class="form-group">
            <label for="inputDescription">Message</label>
            <textarea id="inputDescription" class="form-control" rows="4"></textarea>
            </div>
            <div class="form-group">
            <label for="inputStatus">Status</label>
            <select class="form-control custom-select">
                <option selected disabled>Select one</option>
                <option>Approved</option>
                <option>Pending</option>
                <option>For Review</option>
            </select>
            </div>
            <div class="form-group">
            <label for="inputClientCompany">Client Company</label>
            <input type="text" id="inputClientCompany" class="form-control">
            </div>
            <div class="form-group">
            <label for="inputProjectLeader">Client Representative</label>
            <input type="text" id="inputProjectLeader" class="form-control">
            </div>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<script>
    var initialPreview = <?= (empty($this->document)) ? "''" : json_encode($initialPreview); ?>;
    var initialPreviewAsData = true;
    var initialPreviewFileType = 'pdf';
    var initialPreviewConfig = <?= (empty($this->document)) ? "''" :  json_encode($initialPreviewConfig); ?>;
    var initialPreviewThumbTags = <?= (empty($this->document)) ? "''" :  json_encode($initialPreviewThumbTags); ?>; 
    var param = "/<?= $this->id ?>/<?= $this->shipment['shipment_id']; ?>/<?= $this->shipment['type']; ?>";
    var shipment_id = "<?= $this->shipment['shipment_id']; ?>";
    var document_type = "<?= $this->shipment['type']; ?>";
    var user_id = <?= $_SESSION['user']; ?>;
    var upload_button, options = "";
    <?php if (isset($document_settings->doctracker->push_document)): ?>
    upload_button = '<button type="button" ' +
            'class="kv-file-upload btn btn-sm btn-kv btn-default btn-outline-secondary" ' +
            'title="Upload to CargoWise"{dataUrl}{dataKey} ' +
            'data-doc_id="{id}" data-doc_status="{status}">' +
            '<i class="fas fa-upload"></i>' +
            '</button>\n';
    <?php endif; ?>
    var delete_button = <?= (isset($document_settings->doctracker->delete_document)) ? 'true' : 'false'; ?>;
</script>
<?= $this->getJS(); ?>
