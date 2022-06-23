<?= $this->getCSS(); ?>
<?php 
$approved = 0;
$pending = 0;
$freview = 0;
$status_icon = "fa-thumbs-down";
$document_settings = (isset($this->user_settings[0])) ? json_decode($this->user_settings[0]->document) : NULL;
if(!empty($this->document)) {
    foreach ($this->document as $key => $file) {
        //if($file->status !== 'deleted') {
            // $initialPreview[] = "<object data='data:application/pdf;base64," . $file->img_data . "' type='text/plain' class='' alt='" . $file->name . "' ><div class='file - preview - other'> <span class='file - icon - 4x'><i class='glyphicon glyphicon-file'></i></span> </div></object>";
            // $initialPreview[] = base$file->img_data;
            // $path = "E:/A2BFREIGHT_MANAGER/CLIENT_$id/CW_FILE/$shipkey/$pathName/";
            if(false) {
                $server_file = '/filemanager/' . $this->email . '/CW_FILE/' . $file->shipment_num . '/' . $file->type . '/' . $file->name;
            }

            // $server_file = "/document/fileviewer/" . $this->id . "/"  . $file->document_id;
            $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://";
            $server_file = $protocol . "cargomation.com/filemanager/".$this->email."/CW_FILE/".$file->shipment_num."/".$file->type."/" . $file->name;
            $ext = pathinfo($server_file, PATHINFO_EXTENSION);

            // if($ext == 'txt') {
            //     $ext = 'text';
            // } else if (strpos($ext, 'docx') >= 0
            //     || strpos($ext, 'xlsx') >= 0
            //     || strpos($ext, 'pptx') >= 0 ) {
            //     $ext = 'office';
            // } else {
            //     $ext = 'pdf';
            // }

            if ($ext == 'pdf') {
                $ext = 'pdf';
            } else if ($ext == 'txt') {
                $ext = 'text';
            } else if ($ext == 'tif' || $ext == 'ai' || $ext == 'tiff' || $ext == 'eps') {
                $ext = 'gdocs';  
            } else if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png') {
                $ext = 'image'; 
            } else {
                $ext = 'office';
            }

            // switch (true) {
            //     case $ext == 'pdf':
            //         $ext = "pdf";
            //         break;
            //     case strpos($ext, 'docx') >= 0:
            //         $ext = "office";
            //         break;
            //     case strpos($ext, 'xlsx') >= 0:
            //         $ext = "office";
            //         break;
            //     // If you want to use the value of strpos
            //     case ($pos = strpos($field, 'other_ext')) >= 0:
            //         echo '_term at position ' . $pos;
            //         break;
            //     default:
            //         # code...
            //         break;
            // }

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
                                        'description' => $file->name,
                                        'width' => '200px',
                                        'type' => $ext,
                                        'source' => $file->upload_src,
                                        'size' => " ",
                                        // 'previewAsData' => true,
                                        'frameClass' => 'd-' . $file->document_id . ' b-' . $file->status,
                                        'key' => $file->document_id,
                                        'dataKey' => $file->document_id,
                                        'dataUrl' => $file->document_id,
                                        'extra' => ['id' => $file->document_id,
                                                    'name' => $file->name,
                                                    'type' => $file->type,
                                                    'status' => $file->status,
                                                    'source' => $file->upload_src,
                                                    'date' => $file->saved_date]];
            $initialPreviewThumbTags[] = [  '{status}' => $file->status,
                                            '{title}' => $file->name,
                                            '{origin}' => $file->upload_src,
                                            '{icon}' => $status_icon,
                                            '{date}' => $file->saved_date,
                                            '{type}' => $file->type,
                                            '{id}' => $file->document_id];
        //}
    }
} ?>

<div id="document-upload" style="display: block;">
    <div class="row">
        <div class="col-md-5">
            <h5><b></b><span><?= $this->shipment_info[0]->shipment_num; ?></span></h5>
        </div>
        <div class="col-md-5">
            <!-- <h5><b>Status: </b><span>
                <span class="text-success">Approved (<?= $approved; ?>)</span>
                <span class="text-danger"> Pending (<?= $pending; ?>) </span>
                <span class="text-warning">For Review (<?= $freview; ?>)</span> 
            </h5> -->
            <h5><b>Type: </b><span><?= !is_array($this->shipment['type'])? $this->shipment['type'] : "ALL"; ?></span></h5>
        </div>
        <div class="col-md-2">
            <h5><b>Total: </b><span><?= count($this->document); ?></span></h5>
        </div>
    </div>

    <div class="card card-outline card-primary collapsed-card" style="transition: all 0.15s ease 0s; height: inherit; width: inherit;">
        <div class="card-header">
            <?php if($this->shipment['type'] == ""): ?>
            <h3 class="card-title">
                <button type="button" class="btn btn-default bg-primary btn-sm" data-card-widget="collapse">
                Request a document
                </button>
            </h3>
            <?php else: ?>
            <h3 class="card-title">More Shipment Info</h3>
            <?php endif; ?>
            <?php
                $type2 = $this->shipment['type'];
                if (is_array($this->shipment['type'])) {
                    $type2 = "";
                }
            ?>
            <div class="card-tools">
                <button type="button" class="btn btn-tool d-none" data-card-widget="card-refresh" data-source="/shipment/document/<?= $this->shipment['shipment_id'] . '/' . $type2 ?>" data-source-selector="#card-refresh-content" data-load-on-init="false">
                <i class="fas fa-sync-alt"></i>
                </button>
                <button type="button" class="btn btn-tool d-none" data-card-widget="maximize">
                <i class="fas fa-expand"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
                </button>
                <button type="button" class="btn btn-tool d-none" data-card-widget="remove" disabled>
                <i class="fas fa-times"></i>
                </button>
            </div>
            <!-- /.card-tools -->
        </div>
        <!-- /.card-header -->
        <div class="card-body" style="display: none;">
            <div class="row">
            <?php if($this->shipment['type'] == ""): ?>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Document Types</label>
                        <select class="multiple-document" name="doc_type[]" multiple="multiple" data-placeholder="Select a document type">
                        <?php foreach ($this->document_type as $key => $value): ?>
                            <option value="<?= $value->type; ?>">
                            <?= $value->type . " - " . $value->description; ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="multi_request">Request document</button>
                    </div>
                </div>
            <?php else: ?>
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
                </div>
            <?php endif; ?>
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
    // var initialPreviewAsData = true;
    // var initialPreviewFileType = 'pdf';
    var initialPreviewConfig = <?= (empty($this->document)) ? "''" :  json_encode($initialPreviewConfig); ?>;
    var initialPreviewThumbTags = <?= (empty($this->document)) ? "''" :  json_encode($initialPreviewThumbTags); ?>; 
    var param = "/<?= $this->id ?>/<?= $this->shipment['shipment_id']; ?>/<?= $type2; ?>";
    var shipment_id = "<?= $this->shipment['shipment_id']; ?>";
    var document_type = "<?= $type2; ?>";
    var user_id = <?= $_SESSION['user']; ?>;
    var options = "";
    var btn_upload = "";
    var btn_status = "";
    var btn_edit = "";
    var btn_comment = "";
    var btn_delete = "";
<?php if ($this->role->role_id != 4): ?>
    <?php if (isset($document_settings->doctracker->push_document)): ?>
    btn_upload = '<button type="button" ' +
                'class="kv-file-push btn btn-sm btn-kv btn-default btn-outline-secondary" ' +
                'title="Upload to CargoWise"{dataUrl}{dataKey} ' +
                'data-doc_id="{id}" data-doc_status="{status}">' +
                '<i class="fas fa-cloud"></i>' +
                '</button>\n';
    <?php endif; ?>
    btn_edit = '<button type="button" ' +
                'class="kv-file-edit btn btn-sm btn-kv btn-default btn-outline-secondary" ' +
                'title="Request for Edit"{dataUrl}{dataKey} ' +
                'data-doc_id="{id}" data-doc_status="{status}">' +
                '<i class="fas fa-edit"></i>' +
                '</button>\n';
    btn_comment = '<button type="button" ' +
                'class="kv-file-comment btn btn-sm btn-kv btn-default btn-outline-secondary" ' +
                'title="View Comment"{dataUrl}{dataKey} ' +
                'data-doc_id="{id}" data-doc_status="{status}">' +
                '<i class="fas fa-comment"></i>' +
                '</button>\n';
    btn_status = '<button type="button" ' +
                'class="kv-file-status btn btn-sm btn-kv btn-default btn-outline-secondary" ' +
                'title="Change Status"{dataUrl}{dataKey} ' +
                'data-doc_id="{id}" data-doc_status="{status}">' +
                '<i class="fas {icon} {status}"></i>' +
                '</button>\n';
<?php endif; ?>
    btn_download = '<button type="button" ' +
                'class="kv-file-download btn btn-sm btn-kv btn-default btn-outline-secondary {downloadClass}" ' +
                'title="{caption}" ' + 
                'data-doc_id="{id}" data-url="{downloadUrl}" data-caption="{caption}"> ' +
                '<i class="fas fa-download"></i>' + 
                '</button>\n';
    btn_delete = <?= (isset($document_settings->doctracker->delete_document)) ? 'true' : 'false'; ?>;
</script>
<?= $this->getJS(); ?>
