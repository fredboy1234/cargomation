<!-- Main content -->
<style>
    .dropdown-menu {
        max-height: 336px;
        overflow-y: auto;
    }

    .drop-act {
        position: sticky;
        width: 100%;
        z-index: 999;
        bottom: -7px;
        left: 0;
        background-color: #fff;
        width: 250px;
    }

    .select2-selection__choice {
        color: #333 !important;
    }
    #myModal{
        z-index: 1049;
    } 
    .btn_round {
  width: 35px;
  height: 35px;
  display: inline-block;
  border-radius: 50%;
  text-align: center;
  line-height: 35px;
  margin-left: 10px;
  border: 1px solid #ccc;
  cursor: pointer;
}
.btn_round:hover {
  color: #fff;
  background: #6b4acc;
  border: 1px solid #6b4acc;
}

.btn_content_outer {
  display: inline-block;
  width: 85%;
}
.close_c_btn {
  width: 30px;
  height: 30px;
  position: absolute;
  right: 10px;
  top: 0px;
  line-height: 30px;
  border-radius: 50%;
  background: #ededed;
  border: 1px solid #ccc;
  color: #ff5c5c;
  text-align: center;
  cursor: pointer;
}

.add_icon {
  padding: 10px;
  border: 1px dashed #aaa;
  display: inline-block;
  border-radius: 50%;
  margin-right: 10px;
}
.add_group_btn {
  display: flex;
}
.add_group_btn i {
  font-size: 32px;
  display: inline-block;
  margin-right: 10px;
}

.add_group_btn span {
  margin-top: 8px;
}
.add_group_btn,
.clone_sub_task {
  cursor: pointer;
}

.sub_task_append_area .custom_square {
  cursor: move;
}

.del_btn_d {
  display: inline-block;
  position: absolute;
  right: 20px;
  border: 2px solid #ccc;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  line-height: 40px;
  text-align: center;
  font-size: 18px;
}
#s_headcus{
    background-color: #3778be;
    color: #ffffff;
}
</style>
<section class="content">
    <div class="container-fluid">
        <div id="fsearch" class="card card-default collapsed-card position-relative">
            <div class="card-header">
                <ul class="nav nav-pills float-left">
                    <li class="nav-item fsearch" data-card-widget="collapse"><a class="nav-link active" href="#vert-tabs" data-toggle="tab">Filter and Search</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">View</a></li> -->
                    <!-- <li class="nav-item" data-card-widget="collapse"><a class="nav-link" href="#vert-tabs-settings" data-toggle="tab">Settings</a></li> -->
                </ul>
                <div class="card-tools colp" style="line-height: 2.49em;">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: none;">
                <div class="tab-content2" id="">
                    <div class="tab-pane" id="vert-tabs">
                        <div class="row">
                            <div class="col-12 col-lg-2 col-md-2 col-sm-12">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-search-tab" data-toggle="pill" href="#vert-tabs-search" role="tab" aria-controls="vert-tabs-search" aria-selected="true">Advanced Search</a>
                                    <a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill" href="#vert-tabs-settings" role="tab" aria-controls="vert-tabs-settings" aria-selected="false">Column Filters</a>
                                    <a class="nav-link" id="vert-tabs-save-tab" data-toggle="pill" href="#vert-tabs-save" role="tab" aria-controls="vert-tabs-save" aria-selected="false">Saved Searches</a>
                                    <?php if(false): ?>
                                    <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Profile</a>
                                    <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Messages</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-12 col-lg-10 col-md-10 col-sm-12">
                                <div class="tab-content" id="vert-tabs-tabContent">
                                    <div class="tab-pane text-left fade active show" id="vert-tabs-search" role="tabpanel" aria-labelledby="vert-tabs-search-tab">
                                        <div class="active tab-pane" id="activity">
                                            <form id="addvance-search-form">
                                            <?php 
                                              include(VIEW_PATH."_template/module/searchfilter.php");
                                            ?>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-settings" role="tabpanel" aria-labelledby="vert-tabs-settings-tab">
                                        <div class="tab-pane" id="settings">
                                            <select multiple="multiple" size="10" name="settings-dual" title="settings-dual">
                                                <?php $settings = json_decode($this->user_settings); ?>
                                                <?php foreach ($settings as $value) { ?>
                                                    <?php $selected = ($value->index_check == "true" ? "selected='selected'" : ""); ?>
                                                    <?php $level = (isset($value->index_lvl) ? $value->index_lvl : "shipment") ?>
                                                    <option id="<?= $value->index ?>" 
                                                    class="settings-menu" 
                                                    value="<?= $value->index_value ?>" 
                                                    <?= $selected ?> 
                                                    data-index="<?= $value->index; ?>"
                                                    data-sort="<?= $value->index_sortable; ?>"
                                                    data-text="<?= $value->index_name ?>" 
                                                    lvl="<?= $level ?>">
                                                        <?= $value->index_name ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <div class="col-md-2 float-right parent-settings">
                                                <?php if (is_array($this->settings_user)) { ?>
                                                    <?php $settings_id = $this->settings_user[0]->id; ?>
                                                    <button id="reset-settings" type="button" data-setting-id="<?= $settings_id ?>" class="btn btn-block btn-danger">Set Default</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-save" role="tabpanel" aria-labelledby="vert-tabs-save-tab">
                                        <div class="tab-pane" id="save">
                                            <div class="row">
                                                <div class="col-sm-4 form_sec_outer_task ">
                                                    <div id ="row_head" class="row">
                                                        <div class="col-md-4">
                                                            <label>Save Search</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 p-0">
                                                        <div class="col-md-12 p-0">
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <select id="save_search" multiple="" class="form-control" size="6"></select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 form_sec_outer_task ">
                                                    <div id ="row_head" class="row">
                                                        <div class="col-md-4">
                                                            <label>Search Query</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 p-0">
                                                        <div class="col-md-12 p-0">
                                                            <div class="row">
                                                                <div class="form-group col-md-12">
                                                                    <div id="query_text">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!--
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Search Query</label>
                                                        <div id="query_text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Recent Search</label>
                                                        <select id="recent_search" multiple="" class="form-control">
                                                        </select>
                                                    </div>
                                                </div>
                                                -->
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <button type="button" id="resetSearch" class="btn btn-warning">
                                                        <i class="fas fa-undo"></i> Reset Selection
                                                    </button>
                                                    <button type="button" id="deleteSearch" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i> Delete Query
                                                    </button>
                                                </div>
                                                <div class="col-md-8">
                                                    <button type="button" id="loadSaved" class="btn btn-success">
                                                        <i class="fas fa-search"></i> Load Saved Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <?php if(false): ?>
                                    <!--
                                    <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                        Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam. 
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
                                        Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna. 
                                    </div>
                                    -->
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab2">

                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab3">

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer" style="display: none;">
            </div>
            <!-- /.card-footer -->
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"></h3>

                        <div class="card-tools">
                            <div class="input-group input-group-sm" style="width: 150px;">
                                <input id="doc_search" type="text" name="table_search" class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 ttable" style="height: auto;">
                        <?php usort($settings, function ($a, $b) {
                            return $a->index_value - $b->index_value;
                        }); ?>
                        <table class="table table-hover table-head-fixed text-nowrap">
                            <thead>
                                <tr>
                                    <?php foreach ($settings as $key => $value) { ?>
                                        <?php if($value->index_lvl == "document"): ?>
                                            <th id="s_headcus"><?= strtoupper($value->index) ?></th>
                                        <?php else:  ?>
                                            <th id="s_headcus"><?= $value->index_name ?></th>
                                        <?php endif;  ?>
                                    <?php } ?>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <?php if(false): ?>
                    <div class="parent-assign dropdown d-none notransition">
                        <a class="col-md-1 col-sm-1 assign-button" href="#!" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-share-square"></i>
                        </a>
                        <div class="dropdown-menu dropdown-primary" aria-labelledby="dropdownMenuLink">
                            <?php foreach ($this->child_user as $user) { ?>
                                <a class="dropdown-item" href="#!">
                                    <button type="button" class="assign d-inline-block btn btn-success btn-xs" data-userid="<?= $user->id ?>" data-shipid="">Assign </button>
                                    <span class="d-inline-block"><?= $user->first_name . ' ' . $user->last_name ?></span>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- /.row -->
    </div>
</section>

</div>

<div class="modal fade" id="myModal">
    <div class="modal-dialog modal-lg" style="width:100%; max-width:1100px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Document</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="loader-wrapper2" class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <div id="document_action">
                <?php if($this->role->role_id != 4): ?>
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="select-all">
                        <label for="select-all" style="margin-top: 7px;margin-right: 10px;">
                            Select all
                        </label>
                    </div>
                    <?php if(false): ?>
                    <form class="d-inline-block mr-3" action="/action_page.php">
                        <select name="bulk-action" id="bulk-action" class="form-control">
                            <option value="" selected="" disabled="" hidden="">Choose bulk action</option>
                            <optgroup label="Bulk update status:" data-option="status">
                                <option value="approved">Approve</option>
                                <option value="pending">Pending</option>
                            </optgroup>
                            <optgroup label="Bulk document action" data-option="action">
                                <option value="push">Push</option>
                                <option value="deleted">Delete</option>
                            </optgroup>
                        </select>
                    </form>
                    <?php endif; ?>
                     <button id="push_selected" 
                        data-action="push" data-text="Push" data-option="action" 
                        data-toggle="tooltip" data-placement="top" 
                        class="btn badge-primary" type="button" title="Upload to CargoWise"><i class="fas fa-cloud"></i></button>
                    <button id="delete_selected" 
                        data-action="deleted" data-text="Delete" data-option="action" 
                        data-toggle="tooltip" data-placement="top" 
                        class="btn btn-default bg-danger" type="button" title="Delete Selected File"><i class="fas fa-trash"></i></button>
                    <button id="edit_selected" data-action="edit" data-text="Edit" 
                        data-option="action" data-toggle="tooltip" data-placement="top" 
                        class="btn badge-primary" type="button" title="" data-original-title="Change Document Type">
                        <i class="fas fa-pencil-alt"></i></button>
                    <button id="compare_selected" 
                        data-action="compare" data-text="Compare" data-option="action" 
                        data-toggle="tooltip" data-placement="top" 
                        class="btn badge-primary" type="button" title="Compare Selected File"><i class="fas fa-eye"></i></button>
                    <button id="approve_selected" data-action="approved" data-text="Approve" data-option="status" type="button" title="Approve Selected" data-toggle="tooltip" data-placement="top" class="btn badge-primary"><i id="custom_thumb" class="fas fa-thumbs-up cust"></i></button>
                    <button id="pending_selected" data-action="pending" data-text="Pending" data-option="status" type="button" title="Pending Selected" data-toggle="tooltip" data-placement="top" class="btn btn-default bg-danger"><i id="custom_thumb" class="fas fa-thumbs-down"></i></button>
                    <button id="approve_all" data-action="approved" data-text="Approve All" data-option="status_all" type="button" class="btn badge-primary">Approve All</button>
                    <button id="pending_all" data-action="pending" data-text="Pending All" data-option="status_all" type="button" class="btn btn-default bg-danger">Pending All</button>
                    <button type="button" class="btn btn-primary" id="request">Request a document</button>
                <?php endif; ?>
                </div>
                <div id="go_back" style="display:none">
                    <button type="button" class="btn btn-default" onClick="goBack()">Go Back</button>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<div class="modal fade" id="loadermodal">
    <div class="modal-dialog modal-lg" style="width:100%; max-width:1088px">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Updating Shipment</h4>
            </div>
            <div class="modal-body">
                <div id="loader-wrapper" class="d-flex justify-content-center">
                    <div class="spinner-grow text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-secondary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-success" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-danger" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-warning" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-info" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-light" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <div class="spinner-grow text-dark" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- Shipment info modal -->
<div class="modal fade" id="shipmentModal">
  <div class="modal-dialog modal-lg" style="width:100%; max-width:1088px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Shipment Info</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Loading&hellip;</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- Shipment info modal -->
<div class="modal fade" id="compare-modal">
  <div class="modal-dialog modal-lg" style="width:100%; max-width:1088px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Shipment Info</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Loading&hellip;</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->


<script>
    var userData = <?php echo json_encode($this->user_settings); ?>;
    var userReset = <?php echo json_encode($this->user); ?>;
    var userrole = <?php echo json_encode($this->role) ?>;
    var theme = <?php echo json_encode($this->selected_theme) ?>;
    var user_id = <?php echo $this->user_id ?>;
    var role_id = <?php echo $this->role->role_id ?>;
</script>
