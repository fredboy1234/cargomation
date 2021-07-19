<!-- Main content -->
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
                                </div>
                            </div>
                            <div class="col-12 col-lg-10 col-md-10 col-sm-12">
                                <div class="tab-content" id="vert-tabs-tabContent">
                                    <div class="tab-pane text-left fade active show" id="vert-tabs-search" role="tabpanel" aria-labelledby="vert-tabs-search-tab">
                                    <div class="active tab-pane" id="activity">
                                        <form id="addvance-search-form">
                                        <div class="row">
                                            <div class="col-md-4" data-select2-id="29">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">Shipment ID</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="inputEmail3" name="shipment_id" placeholder="Ex.: SHP001">
                                                </div>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">ETA</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="inputEmail3" name="ETA" placeholder="">
                                                </div>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">Client Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="inputEmail3" name="client_name" placeholder="">
                                                </div>
                                            </div>
                                            <!-- /.form-group -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-4" data-select2-id="29">
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">Consignee</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="inputEmail3" name="consignee" placeholder="">
                                                </div>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">Consignor</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="inputEmail3" name="consignor" placeholder="">
                                                </div>
                                            </div>
                                            <!-- /.form-group -->
                                            <div class="form-group row">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">Container #</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="inputEmail3" name="container" placeholder="">
                                                </div>
                                            </div>
                                            <div class="form-group row d-none">
                                                <label for="inputEmail3" class="col-sm-4 col-form-label">Port of Loading</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" id="inputEmail3" name="pol" placeholder="">
                                                </div>
                                            </div>
                                            <!-- /.form-group -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-4" data-select2-id="29">
                                            <div class="form-group row">
                                                
                                                <div class="col-sm-6">
                                                    <!-- checkbox -->
                                                    <label for="inputEmail3" class="col-sm-6 col-form-label">Status</label>
                                                    <input id="status" type="hidden" name="status" value="">
                                                    <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input statusC" name="stat" type="checkbox" checked value="Approved">
                                                        <label class="form-check-label">Approved</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input statusC" name="stat" type="checkbox" checked value="Pending">
                                                        <label class="form-check-label">For Approval</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input statusC" name="stat" type="checkbox" checked value="Missing">
                                                        <label class="form-check-label">Missing</label>
                                                    </div>
                                                    </div>
                                                </div> 
                                                <div class="col-sm-6">
                                                    <!-- radio -->
                                                    <label for="inputEmail3" class="col-sm-6 col-form-label">Origin</label>
                                                    <div class="form-group">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="origin" value="cargowise">
                                                        <label class="form-check-label">Cargowise</label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="origin" value="hub" checked>
                                                        <label class="form-check-label">Hub</label>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                                <input type="hidden" name="post_trigger" value="">
                                            <div class="col-sm-6 d-inline-block">
                                                <button id="advance-search-btn" type="button" class="btn btn-block btn-primary">Search</button>
                                            </div>
                                            <div class="col-sm-2 d-inline-block">
                                                <button id="reset-search" type="button" class="btn"><i class="fas fa-sync-alt"></i></button>
                                            </div>
                                            <!-- /.form-group -->
                                            </div>
                                            <!-- /.col -->
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
                                        <?php $settings = json_decode($this->user_settings);?>
                                        <?php foreach($settings as $value){?>
                                            <?php $selected = ($value->index_check=="true"?"selected='selected'":"");?>
                                            <option id="<?=$value->index_value?>" class="settings-menu" value="<?=$value->index_value?>" <?=$selected?> data-text="<?=$value->index_name?>">
                                            <?=$value->index_name?>
                                            </option>
                                        <?php }?>
                                        </select>
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
                <div class="card-body table-responsive p-0 ttable" style="height: 500px;">
                  <?php usort($settings, function($a, $b) {return $a->index_value - $b->index_value;});?>                     
                  <table class="table table-hover table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                      <?php foreach($settings as $key=>$value){ ?>
                        <th><?=$value->index_name?></th>
                      <?php }?>
                      </tr>
                    </thead>
                  </table>
                </div>
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
  <div class="modal-dialog modal-lg" style="width:100%; max-width:1088px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Document</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Loading&hellip;</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
  var userData = <?php echo json_encode($settings);?>;
  var userReset = <?php echo json_encode($this->user);?>; 
  var userrole = <?php echo json_encode($this->role)?>;
  var theme = <?php echo json_encode($this->selected_theme)?>;
</script>