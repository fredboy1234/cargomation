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
</style>
<?php $searchfilter = $this->searchfilter?>
<section class="content">
    <div class="container-fluid">
        <div class="card card-default collapsed-card">
            <div class="card-header">
                <ul class="nav nav-pills float-left">
                    <li class="nav-item" data-card-widget="collapse"><a class="nav-link active" href="#vert-tabs" data-toggle="tab">Filter and Search</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">View</a></li> -->
                    <!-- <li class="nav-item" data-card-widget="collapse"><a class="nav-link" href="#vert-tabs-settings" data-toggle="tab">Settings</a></li> -->
                </ul>
                <div class="card-tools" style="line-height: 2.49em;">

                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
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
                                    <!-- 
                                    <a class="nav-link" id="vert-tabs-profile-tab" data-toggle="pill" href="#vert-tabs-profile" role="tab" aria-controls="vert-tabs-profile" aria-selected="false">Profile</a>
                                    <a class="nav-link" id="vert-tabs-messages-tab" data-toggle="pill" href="#vert-tabs-messages" role="tab" aria-controls="vert-tabs-messages" aria-selected="false">Messages</a>
                                    -->
                                    <a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill" href="#vert-tabs-settings" role="tab" aria-controls="vert-tabs-settings" aria-selected="false">Column Filters</a>
                                    <a class="nav-link d-none" id="vert-tabs-settings-tab" data-toggle="pill" href="#vert-tabs-assign" role="tab" aria-controls="vert-tabs-assign" aria-selected="false">Assign Shipment</a>
                                </div>
                            </div>
                            <div class="col-12 col-lg-10 col-md-10 col-sm-12">
                                <div class="tab-content" id="vert-tabs-tabContent">
                                    <div class="tab-pane text-left fade active show" id="vert-tabs-search" role="tabpanel" aria-labelledby="vert-tabs-search-tab">
                                        <div class="active tab-pane" id="activity">
                                            <form id="addvance-search-form">
                                                <div class="row">
                                                    <div class="col-md-12 form_sec_outer_task ">
                                                        <div class="row">
                                                            <div class="col-md-12 p-2 mb-3">
                                                            </div>
                                                            <div class="col-md-3">
                                                                <label>Search</label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label>Type</label>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label>Value</label>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label> </label>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label> </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12 p-0">
                                                            <div class="col-md-12 form_field_outer p-0">
                                                                <div class="row form_field_outer_row 1" section="1">
                                                                <div class="form-group col-md-3">
                                                                    <select name="search[]" id="no_search_1" class="form-control search-list">
                                                                        <option>--Select type--</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-2">
                                                                    <select name="type[]" id="no_type_1" class="form-control">
                                                                        <option>--Select type--</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <input  name="value[]" id="no_value_1" type="text" class="form-control w_90" placeholder="Enter search value">
                                                                </div>
                                                                <div class="form-group col-md-1">
                                                                    <select name="cond[]" id="no_cond_1" class="form-control exclude" disabled>
                                                                        <option value="OR">OR</option>
                                                                        <option value="AND">AND</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-2 add_del_btn_outer">
                                                                    <button class="btn_round add_node_btn_frm_field" title="Copy or clone this row">
                                                                    <i class="fas fa-copy"></i>
                                                                    </button>
                                                                    <button class="btn_round remove_node_btn_frm_field" disabled="">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                    </button>
                                                                </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12 p-0">
                                                                <div class="row">
                                                                    <div class="form-group col-md-4">
                                                                        <select name="" id="add_filters" class="form-control search-list add_new_frm_field_btn">
                                                                            <option>--Select search type--</option>
                                                                            <option value="shipment_id">--Add search type--</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mt-2 p-0">
                                                        <button type="submit" id="searchFilter" class="btn btn-primary"><i class="fas fa-search"></i> Search Data </button>
                                                        <button type="button" id="clearFilter" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Clear Filter </button>
                                                    </div>
                                                </div>
                                                <!-- /.row -->
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!--
                                    <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                        Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam. 
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
                                        Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna. 
                                    </div>
                                    -->
                                    <div class="tab-pane fade" id="vert-tabs-settings" role="tabpanel" aria-labelledby="vert-tabs-settings-tab">
                                        <div class="tab-pane" id="settings">
                                            <select multiple="multiple" size="10" name="settings-dual" title="settings-dual">
                                                <?php $settings = json_decode($this->user_settings); ?>
                                                <?php foreach ($settings as $value) { ?>
                                                    <?php $selected = ($value->index_check == "true" ? "selected='selected'" : ""); ?>
                                                    <?php $level = (isset($value->index_lvl) ? $value->index_lvl : "shipment") ?>
                                                    <option id="<?= $value->index_value ?>" class="settings-menu" value="<?= $value->index_value ?>" <?= $selected ?> data-text="<?= $value->index_name ?>" lvl="<?= $level ?>">
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
                                    <div class="tab-pane fade" id="vert-tabs-assign" role="tabpanel" aria-labelledby="vert-tabs-assign-tab">
                                        <div class="tab-pane" id="assign">
                                            <span>Available User with Shipment:</span><br>
                                            <?php if (!empty($this->shipment_from_contact['shipment_contact'])) { ?>
                                                <?php $sh = $this->shipment_from_contact['shipment_contact']; ?>
                                                <?php $random_color = array("btn-primary", "btn-secondary", "btn-warning", "btn-success", "btn-danger"); ?>
                                                <ul>
                                                    <?php foreach ($sh as $k => $s) { ?>
                                                        <li class="dropdown  dropright">
                                                            <p class="dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                <?php echo $k ?>
                                                            </p>
                                                            <div id="drop-list" class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                                <div class="drop-act-search dropdown-item-text">
                                                                    <input onkeyup="filterFunction()" class="mb-3 form-control" type="text" placeholder="Search Shipment">
                                                                </div>
                                                                <?php foreach ($s as $sc) { ?>
                                                                    <a class="dropdown-item" href="#" data-search="<?= $sc->shipment_num ?>">
                                                                        <span> <?= $sc->shipment_num ?></span>
                                                                        <?php if ($sc->shipment_assigned == 'not-assigned') { ?>
                                                                            <button type="button" class="btn-assign btn d-inline-block btn-success btn-sm" data-userid="<?= $sc->userid ?>" data-shipid="<?= $sc->shipmentid ?>">
                                                                                Assign
                                                                            </button>
                                                                        <?php } else { ?>
                                                                            <button type="button" class="btn-unassign btn d-inline-block btn-danger btn-sm" data-userid="<?= $sc->userid ?>" data-shipid="<?= $sc->shipmentid ?>">Unassign</button>
                                                                        <?php } ?>

                                                                    </a>
                                                                <?php } ?>
                                                                <div class="drop-act dropdown-item-text">
                                                                    <div class="dropdown-divider"></div>
                                                                    <button type="button" class="btn d-inline-block btn-primary btn-sm" id="assignall">Assign All</button>
                                                                    <button type="button" class="btn d-inline-block btn-danger btn-sm" id="unassign">Unassign All</button>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
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
                        <h3 class="card-title">Shipment</h3>

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
                                        <th><?= $value->index_name ?></th>
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
                <div id="loader-wrapper" class="d-flex justify-content-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <div class="">
                    <?php if($this->role->role_id != 4): ?>
                        <div class="icheck-primary d-inline">
                            <input type="checkbox" id="select-all">
                            <label for="select-all" style="margin-top: 7px;margin-right: 10px;">
                                Select all
                            </label>
                        </div>
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
                        <button id="approve_all" data-value="approved" type="button" class="btn btn-default bg-success">Approve All</button>
                        <button id="pending_all" data-value="pending" type="button" class="btn btn-default bg-danger">Pending All</button>
                        <button type="button" class="btn btn-primary" id="request">Request a document</button>
                    <?php endif; ?>
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



<script>
    var userData = <?php echo json_encode($this->user_settings); ?>;
    var userReset = <?php echo json_encode($this->user); ?>;
    var userrole = <?php echo json_encode($this->role) ?>;
    var theme = <?php echo json_encode($this->selected_theme) ?>;
    var user_id = <?php echo $this->user_id ?>;
    var role_id = <?php echo $this->role->role_id ?>;
    var searchfilter = <?php echo json_encode($this->searchfilter)?>;
</script>
