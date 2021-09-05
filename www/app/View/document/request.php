<?= $this->getCSS(); ?>
<script>
var options = [
<?php
if(is_array($this->emailList)){
    //var_dump($this->emailList[0]);
    foreach($this->emailList as $key => $value){
        echo "{ email: '" . $value->email . "', first_name: '" . $value->first_name . "', last_name: '" . $value->last_name . "' },";
    }
}
?>
];
</script>
<?php 
$title = "";
$request_type = "";
$master_bill = "";
if(is_numeric($this->document)) {
    // $this->results[0]->name;
    $title = 'Request to edit document: ID [#' . $this->document . "]";
    $request_type = 'edit';
    $subject_ph = 'Cargomation | Request for Document Update';
    $template_msg = 'Please provide the updated document with ID #' . $this->document . ' for this shipment.';
} else {
    $title = 'Request for missing document: TYPE [' . $this->document  . "]";
    $request_type = 'new';
    $subject_ph = 'Cargomation | Request for Missing Document';
    $master_bill = " with a master bill " . $this->shipment[0]->master_bill;
    $template_msg = 'Please provide the missing [' . $this->document . '] documents for this shipment' . $master_bill;
} ?>
 
<div id="document-request" style="display: block;">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"> <?= $title; ?> </h3>
            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <form id="form-modal" action="<?= $this->makeUrl("document/putDocumentRequest"); ?>" method="post">
                <div class="form-group">
                    <label for="recipient">To</label>
                    <input type="email" id="recipient" name="recipient" class="form-control contacts" placeholder="Ex: recipient@mail.com" required>
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" class="form-control" placeholder="<?= $subject_ph;?>" value="<?= $subject_ph;?>" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="4" required><?= $template_msg; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="recipient">From</label>
                    <input type="email" id="sender" name="sender" class="form-control mb-3" placeholder="<?= $this->email; ?>" 
                    value="<?= $this->email; ?>" required>
                    <div class="icheck-primary">
                        <input type="checkbox" name="use_email" id="checkboxPrimary1" checked>
                        <label for="checkboxPrimary1">Use email as "sender".</label>
                    </div>
                </div>
                <?php if(false && empty($this->document)): ?>
                <div class="form-group">
                    <label for="document_type">Document Type</label>
                    <select name="document_type" class="form-control custom-select">
                        <option selected disabled>Select one</option>
                        <?php $document_type = array(
                            'hbl' => 'HBL',
                            'pkd' => 'PKD',
                            'civ' => 'CIV'
                        ); ?>
                        <?php foreach( $document_type as $key => $value ): ?>
                        <option value="<?php echo $key ?>"><?php echo $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <input type="hidden" id="user_id" name="user_id" class="form-control" value="<?= $this->user_id; ?>">
                    <input type="hidden" id="shipment_num" name="shipment_num" class="form-control" value="<?= $this->shipment_num; ?>">
                    <input type="hidden" id="request_type" name="request_type" class="form-control" value="<?= $request_type; ?>">
                    <?php if(!empty($this->document)): ?>
                    <input type="hidden" id="document_type" name="document_type" class="form-control" value="<?= $this->document; ?>">
                    <?php elseif(is_numeric($this->document)): ?>
                    <input type="hidden" id="document_type" name="document_type" class="form-control" value="<?= $this->results[0]->type; ?>">
                    <input type="hidden" id="document_id" name="document_id" class="form-control" value="<?= $this->document; ?>">
                    <?php endif; ?>
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-default" id="go_back">Go Back</button>
                    <button type="button" class="btn btn-primary" id="submit">Send Request</button>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
        <div class="card-footer"></div>
        <!-- /.card-footer -->
    </div>
    <!-- /.card -->
</div>
<?= $this->getJS(); ?>