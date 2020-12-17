<!DOCTYPE html>
<!-- release v5.1.4, copyright 2014 - 2020 Kartik Visweswaran -->
<!--suppress JSUnresolvedLibraryURL -->
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>File Manager</title>

<?php if(false): ?> 
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" crossorigin="anonymous">
<?php endif; ?>
    <link href="/bower_components/bootstrap-fileinput/css/fileinput.css" media="all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" crossorigin="anonymous">
    <link href="/bower_components/bootstrap-fileinput/themes/explorer-fas/theme.css" media="all" rel="stylesheet" type="text/css"/>
<?php if(false): ?> 
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="/bower_components/bootstrap-fileinput/js/plugins/piexif.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/js/plugins/sortable.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/js/fileinput.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/js/locales/fr.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/js/locales/es.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/themes/fas/theme.js" type="text/javascript"></script>
    <script src="/bower_components/bootstrap-fileinput/themes/explorer-fas/theme.js" type="text/javascript"></script>
<?php endif; ?>
<?= $this->getCSS(); ?>
</head>
<body>

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

        $server_file = "http://a2bfreighthub.com/admin/fileviewer/" . $this->id . "/"  . $file->document_id;

        $initialPreview[] = $server_file;
        $initialPreviewConfig[] = ['caption' => $file->name,
                                   'width' => '200px',
                                    'type' => 'pdf',
                                    'size' => " ",
                                    'previewAsData' => true,
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

<div id="document">
    <div class="row">
        <div class="col-md-5">
            <h5><b>Shipment ID: </b><?= $this->shipment['shipment_id']; ?></h5>
            <h5><b>Type: </b><?= !empty($this->shipment['type'])? $this->shipment['type'] : "ALL"; ?></h5>
            
        </div>
        <div class="col-md-7">
            <h5><b>Status: </b>
                <span class="text-success">Approved (<?= $approved; ?>)</span>
                <span class="text-danger"> Pending (<?= $pending; ?>) </span>
                <span class="text-warning">For Review (<?= $freview; ?>)</span> 
            </h5>
            <h5><b>Total: </b><?= count($this->document); ?></h5>
        </div>
    </div>
    <div class="file-loading" style="display: none;">
        <input id="input" name="input[]" type="file" accept="application/*" multiple>
    </div>
    <div id="kv-error-1" style="margin-top:10px; display:none"></div>
    <div id="kv-success-1" class="alert alert-success" style="margin-top:10px; display:none"></div>
</div>
<div id="document">

</div>

    <script>
        var initialPreview = <?= (empty($this->document)) ? "''" : json_encode($initialPreview); ?>;
        var initialPreviewAsData = true;
        var initialPreviewFileType = 'pdf';
        var initialPreviewConfig = <?= (empty($this->document)) ? "''" :  json_encode($initialPreviewConfig); ?>;
        var initialPreviewThumbTags = <?= (empty($this->document)) ? "''" :  json_encode($initialPreviewThumbTags); ?>; 
        var param = "/<?= $this->id ?>/<?= $this->shipment['shipment_id']; ?>/<?= $this->shipment['type']; ?>";
    </script>
    <?= $this->getJS(); ?>
</body>

</html>