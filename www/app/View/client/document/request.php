<?= $this->getCSS(); ?>
<?php if(is_numeric($this->document)) {
    $titile = 'Edit request for document id: [#' . $this->document . "]";
} else {
    $titile = 'Document request for document type: [' . $this->document  . "]";
} ?>
<div id="document-request" style="display: block;">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title"> <?= $titile; ?> </h3>
            <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fas fa-minus"></i></button>
            </div>
        </div>
        <div class="card-body">
            <form id="form-modal" action="<?= $this->makeUrl("document/putDocumentRequest"); ?>" method="post">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" class="form-control" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="title">To</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <?php if(false): ?>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" class="form-control custom-select">
                        <option selected disabled>Select one</option>
                        <?php $status = array(
                            'approved' => 'Approved',
                            'pending' => 'Pending',
                            'forreview' => 'For Review'
                        ); ?>
                        <?php foreach( $status as $key => $value ): ?>
                        <option value="<?php echo $key ?>"<?php if( $key == $this->document_status ): ?> selected="selected"<?php endif; ?>><?php echo $value ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <input type="hidden" id="view_id" name="view_id" class="form-control" value="<?= $this->view; ?>">
                    <input type="hidden" id="user_id" name="user_id" class="form-control" value="">
                    <input type="hidden" id="document_id" name="document_id" class="form-control" value="">
                </div>
                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-default" id="go_back">Go Back</button>
                    <button type="button" class="btn btn-primary" id="submit">Request</button>
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