

<?= $this->getCSS(); ?>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h1>Shipment ID: <?= $this->shipment['shipment_id'] ?></h1>
                        <span>Document Type: <?= $this->shipment['type']; ?></span><br>
                        <span>File Count: <?= count($this->document)?></span><br><br>
                        <div class="col-md-12 animated fadeInRight">
                            <div class="row">

                                    <?php foreach ($this->document as $key => $file) : ?>
                                        <div class="file-box col-md-4">
                                            <div class="file">
                                                <a href="#">
                                                    <span class="corner"></span>
                                                    <div class="icon">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </div>
                                                    <div class="file-name">
                                                        
                                                        <?= $file->name; ?>

                                                        <span>
                                                        <small>Upload Source: <?=$file->upload_src;?></small>
                                                        <br>
                                                        <small>Added: <?= date_format(date_create($file->saved_date), "M d, Y"); ?></small>
                                                        </span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="icon">
                                                <div class="status" data-doc_id="<?=$file->document_id?>" data-doc_status="<?= $file->status ?>"><i class="fas <?php echo ($file->status == 'approved') ? 'fa-thumbs-up' : 'fa-thumbs-down'; ?>"></i></div>
                                                <div class="comment" data-type=""><i class="fas fa-comment"></i></div>
                                                <div class="view" data-type=""><i class="fas fa-eye"></i></div>
                                                <div class="edit" data-type=""><i class="fas fa-pencil-alt"></i></div>
                                                <div class="delete" data-type="" style="float: right"><i class="fas fa-trash"></i></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    </div>
                                <div class="file-loading">
                                    <input id="input-44" name="input44[]" type="file" multiple>
                                </div>
                            <div>
                        </div>
                    </div> <!-- /.card-body -->
                </div> <!-- /.card -->
            </div> <!-- /.col -->
        </div> <!-- /.row -->
    </div> <!-- /.container-fluid -->
</section>
</div>
<?= $this->getJS(); ?>