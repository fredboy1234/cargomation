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

<?php if(!empty($this->document)) {
    foreach ($this->document as $key => $file) {
        $initialPreviewData[] = ['caption' => $file->name,
                                   'width' => '200px',
                                    'type' => 'pdf',
                                   'extra' => ['status' => $file->status]];
        $initialPreview[] = $file->name;
        $initialPreviewThumbTags[] = ['{status}' => $file->status, 
                                          '{id}' => $file->document_id, 
                                        '{date}' => $file->saved_date,
                                      '{origin}' => $file->upload_src];
    }
} ?>
    <div>
        <h4>Shipment ID: <?= $this->shipment['shipment_id']; ?></h4>
    </div>
    <div class="file-loading">
        <input id="input" name="input[]" type="file" accept="application/*" multiple>
    </div>
    <div id="kv-error-1" style="margin-top:10px; display:none"></div>
    <div id="kv-success-1" class="alert alert-success" style="margin-top:10px; display:none"></div>
    
    <script>
        var initialPreview = <?= (empty($this->document)) ? "''" : json_encode($initialPreview); ?>;
        var initialPreviewAsData = true;
        var initialPreviewFileType = 'pdf';
        var initialPreviewConfig = <?= (empty($this->document)) ? "''" :  json_encode($initialPreviewData); ?>;
        var initialPreviewThumbTags = <?= (empty($this->document)) ? "''" :  json_encode($initialPreviewThumbTags); ?>; 
    </script>
    <?= $this->getJS(); ?>
</body>

</html>