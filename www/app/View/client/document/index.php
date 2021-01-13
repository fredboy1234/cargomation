<?= $this->getCSS(); ?>
<?php 
$approved = 0;
$pending = 0;
$freview = 0;
if(!empty($this->document)) {
    foreach ($this->document as $key => $file) {
        
        // $initialPreview[] = "<object data='data:application/pdf;base64," . $file->img_data . "' type='text/plain' class='' alt='" . $file->name . "' ><div class='file - preview - other'> <span class='file - icon - 4x'><i class='glyphicon glyphicon-file'></i></span> </div></object>";
        // $initialPreview[] = base$file->img_data;
        // $path = "E:/A2BFREIGHT_MANAGER/CLIENT_$id/CW_FILE/$shipkey/$pathName/";
        if(false) {
            $server_file = '/filemanager/' . $this->email . '/CW_FILE/' . $file->shipment_num . '/' . $file->type . '/' . $file->name;
        }

        $server_file = "/document/fileviewer/" . $this->id . "/"  . $file->document_id;

        $initialPreview[] = $server_file;
        $initialPreviewConfig[] = [ 'caption' => $file->name,
                                    'width' => '200px',
                                    'type' => 'pdf',
                                    'size' => " ",
                                    'previewAsData' => true,
                                    'frameClass' => 'd-' . $file->document_id . ' bg-' . $file->status,
                                    'key' => $file->document_id,
                                    'dataKey' => $file->document_id,
                                    'dataUrl' => $file->document_id,
                                    'extra' => ['status' => $file->status]];
        $initialPreviewThumbTags[] = ['{status}' => $file->status, 
                                          '{id}' => $file->document_id, 
                                        '{date}' => $file->saved_date,
                                      '{origin}' => $file->upload_src];

        if($file->status == 'approved')
            $approved++;
        if($file->status == 'pending')
            $pending++;
        if($file->status == 'review')
            $freview++;
    }

} ?>

<div id="document-upload" style="display: block;">
    <div class="row">
        <div class="col-md-5">
            <h5><b>Shipment ID: </b><?= $this->shipment['shipment_id']; ?></h5>
            <h5><b>Type: </b><?= !empty($this->shipment['type'])? $this->shipment['type'] : "ALL"; ?></h5>
            
        </div>
        <div class="col-md-7">
            <!-- <h5><b>Status: </b>
                <span class="text-success">Approved (<?= $approved; ?>)</span>
                <span class="text-danger"> Pending (<?= $pending; ?>) </span>
                <span class="text-warning">For Review (<?= $freview; ?>)</span> 
            </h5> -->
            <h5><b>Total: </b><?= count($this->document); ?></h5>
        </div>
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
</script>
<?= $this->getJS(); ?>
