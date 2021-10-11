<?php 

$full_name = $this->contact_info->first_name . " " . $this->contact_info->last_name;
$image_src = "/img/default-profile.png";
if(!empty($this->contact_info->image_src)) {
    $image_src = $this->contact_info->image_src;
}
$company_name = $this->contact_info->company_name;
$organization_code = $this->contact_info->organization_code;
$address = $this->contact_info->address;
$address .= " " . $this->contact_info->city;
$address .= " " . $this->contact_info->postcode;

$document_types = "Not set";
$document_types .= implode(", ", array_column($this->document_type, 'type'));

?>
<div class="row">
    <div class="col-md-6">
        <!-- Widget: user widget style 1 -->
        <div class="card card-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-info">
            <h3 class="widget-user-username"><?= $full_name; ?></h3>
            <h6 class="widget-user-desc"><?= $company_name . " (" . $organization_code . ")"; ?></h6>
            </div>
            <div class="widget-user-image">
                <img class="img-circle elevation-2" src="<?= $image_src; ?>" alt="User Avatar">
            </div>
            <div class="card-footer">
            <div class="row">
                <div class="col-sm-4 border-right">
                <div class="description-block">
                    <h5 class="description-header"><?= $this->total_shipment; ?></h5>
                    <span class="description-text">SHIPMENTS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4 border-right">
                <div class="description-block">
                    <h5 class="description-header"><?= $this->document_stats['total_files'][0]->count; ?></h5>
                    <span class="description-text">DOCUMENTS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-4">
                <div class="description-block">
                    <h5 class="description-header">35</h5>
                    <span class="description-text">PRODUCTS</span>
                </div>
                <!-- /.description-block -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
            </div>
        </div>
    <!-- /.widget-user -->
    </div>
    <div class="col-md-6">
        <div class="card card-info">
            <div class="card-header">
                <h3 class="card-title">About Me</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <strong><i class="fas fa-book mr-1"></i> Contact Info</strong>

                <p class="text-muted">
                <strong>Email:</strong> <?= $this->contact_info->email; ?><br>
                <strong>Phone:</strong> <?= $this->contact_info->phone; ?>
                </p>

                <hr>

                <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                <p class="text-muted"><strong>Address:</strong> <?= $address; ?></p>

                <hr>

                <strong><i class="fas fa-file-alt mr-1"></i> Documents</strong>

                <p class="text-muted">
                    <?= $document_types; ?>
                    <span class="tag tag-danger"></span>
                    <span class="tag tag-success"></span>
                    <span class="tag tag-info"></span>
                    <span class="tag tag-warning"></span>
                    <span class="tag tag-primary"></span>
                </p>

                <hr>

                <strong><i class="fas fa-pencil-alt mr-1"></i> Notes</strong>

                <p class="text-muted">No notes added</p>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>