<!-- Main content -->
    <section class="content">
        <div class="container-fluid">

        <div class="card card-default collapsed-card">
          <div class="card-header">
            <ul class="nav nav-pills float-left">
                    <li class="nav-item" data-card-widget="collapse"><a class="nav-link" href="#activity" data-toggle="tab">Advanced Search</a></li>
                    <!-- <li class="nav-item"><a class="nav-link" href="#timeline" data-toggle="tab">View</a></li> -->
                    <li class="nav-item" data-card-widget="collapse"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                </ul>
            <div class="card-tools" style="line-height: 2.49em;">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body" style="display: none;">
          <div class="tab-content">
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
                            <div class="col-sm-6">
                              <button id="advance-search-btn" type="button" class="btn btn-block btn-primary">Search</button>
                            </div>
                            <!-- /.form-group -->
                          </div>
                          <!-- /.col -->
                        </div>
                        <!-- /.row -->
                      </form>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">

                    </div>
                    <!-- /.tab-pane -->

                    <div class="tab-pane" id="settings">

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            <!-- /.form -->
            <?php if(false): ?>
            <h5>Custom Color Variants</h5>
            <div class="row">
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Minimal (.select2-danger)</label>
                  <select class="form-control select2 select2-danger select2-hidden-accessible" data-dropdown-css-class="select2-danger" style="width: 100%;" data-select2-id="12" tabindex="-1" aria-hidden="true">
                    <option selected="selected" data-select2-id="14">Alabama</option>
                    <option>Alaska</option>
                    <option>California</option>
                    <option>Delaware</option>
                    <option>Tennessee</option>
                    <option>Texas</option>
                    <option>Washington</option>
                  </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="13" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-7c88-container"><span class="select2-selection__rendered" id="select2-7c88-container" role="textbox" aria-readonly="true" title="Alabama">Alabama</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Multiple (.select2-purple)</label>
                  <div class="select2-purple">
                    <select class="select2 select2-hidden-accessible" multiple="" data-placeholder="Select a State" data-dropdown-css-class="select2-purple" style="width: 100%;" data-select2-id="15" tabindex="-1" aria-hidden="true">
                      <option>Alabama</option>
                      <option>Alaska</option>
                      <option>California</option>
                      <option>Delaware</option>
                      <option>Tennessee</option>
                      <option>Texas</option>
                      <option>Washington</option>
                    </select><span class="select2 select2-container select2-container--default" dir="ltr" data-select2-id="16" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--multiple" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="-1" aria-disabled="false"><ul class="select2-selection__rendered"><li class="select2-search select2-search--inline"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="searchbox" aria-autocomplete="list" placeholder="Select a State" style="width: 493.5px;"></li></ul></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                  </div>
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <?php endif; ?>
          </div>
          <!-- /.card-body -->
          <div class="card-footer" style="display: none;">
            
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Shipment Document</h3>

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
              <div class="card-body table-responsive p-0" style="height: 500px;">
                <table class="table table-hover table-head-fixed text-nowrap">
                  <thead>
                    <tr>
                      <th>Shipment ID</th>
                      <!-- <th class="d-none">Client Name</th> -->
                      <th>Console ID</th>
                      <th>ETA</th>
                      <th>ETD</th>
                      <th>HBL</th>
                      <th>CIV</th>
                      <th>PKL</th>
                      <th>PKD</th>
                      <th>All</th>
                      <th>Comment</th>
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
  <div class="modal-dialog modal-lg" style="width:100%; max-width:825px">
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