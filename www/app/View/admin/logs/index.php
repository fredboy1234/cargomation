    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

        <div class="card card-default collapsed-card">
          <div class="card-header">
            <h3 class="card-title">Advanced Search</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body" style="display: none;">
            <div class="row">
              <div class="col-md-4" data-select2-id="29">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Shipment ID</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                    </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">ETA</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                    </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Client Name</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                    </div>
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              <div class="col-md-4" data-select2-id="29">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Consignee</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                    </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Consignor</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                    </div>
                </div>
                <!-- /.form-group -->
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-4 col-form-label">Container #</label>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
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
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox">
                          <label class="form-check-label">Client</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox" checked>
                          <label class="form-check-label">For Approval</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="checkbox">
                          <label class="form-check-label">Staff</label>
                        </div>
                      </div>
                    </div> 
                    <div class="col-sm-6">
                      <!-- radio -->
                      <label for="inputEmail3" class="col-sm-6 col-form-label">Origin</label>
                      <div class="form-group">
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1">
                          <label class="form-check-label">Cargowise</label>
                        </div>
                        <div class="form-check">
                          <input class="form-check-input" type="radio" name="radio1" checked>
                          <label class="form-check-label">Hub</label>
                        </div>
                      </div>
                    </div>
                </div>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
            <?php if(false): ?>
            <h5>Custom Color Variants</h5>
            <div class="row">
              <div class="col-12 col-sm-6">
                <div class="form-group">
                  <label>Minimal (.select2-primary)</label>
                  <select class="form-control select2 select2-primary select2-hidden-accessible" data-dropdown-css-class="select2-primary" style="width: 100%;" data-select2-id="12" tabindex="-1" aria-hidden="true">
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
                <h3 class="card-title">User Logs</h3>

                <div class="card-tools">
                  <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

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
                      <th>Log ID</th>
                      <th>Client Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Login</th>
                      <th>IP Address</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>183</td>
                      <td>John Doe</td>
                      <td>client@mail.com</td>
                      <td><span class="badge badge-success">Client</span></td>
                      <td>2020-10-10 14:24:24</td>
                      <td>192.168.0.1</td>
                      <td>File Upload</td>
                    </tr>
                    <tr>
                      <td>219</td>
                      <td>Alexander Pierce</td>
                      <td>client@mail.com</td>
                      <td><span class="badge badge-warning">Exporter</span></td>
                      <td>2020-10-10 14:24:24</td>
                      <td>192.168.0.1</td>
                      <td>File Upload</td>
                    </tr>
                    <tr>
                      <td>657</td>
                      <td>Bob Doe</td>
                      <td>client@mail.com</td>
                      <td><span class="badge badge-success">Client</span></td>
                      <td>2020-10-10 14:24:24</td>
                      <td>192.168.0.1</td>
                      <td>File Upload</td>
                    </tr>
                    <tr>
                      <td>175</td>
                      <td>Mike Doe</td>
                      <td>client@mail.com</td>
                      <td><span class="badge badge-primary">Staff</span></td>
                      <td>2020-10-10 14:24:24</td>
                      <td>192.168.0.1</td>
                      <td>File Upload</td>
                    </tr>
                    <tr>
                      <td>134</td>
                      <td>Jim Doe</td>
                      <td>client@mail.com</td>
                      <td><span class="badge badge-warning">Exporter</span></td>
                      <td>2020-10-10 14:24:24</td>
                      <td>192.168.0.1</td>
                      <td>File Upload</td>
                    </tr>
                    <tr>
                      <td>494</td>
                      <td>Victoria Doe</td>
                      <td>client@mail.com</td>
                      <td><span class="badge badge-warning">Exporter</span></td>
                      <td>2020-10-10 14:24:24</td>
                      <td>192.168.0.1</td>
                      <td>File Upload</td>
                    </tr>
                    <tr>
                      <td>832</td>
                      <td>Michael Doe</td>
                      <td>client@mail.com</td>
                      <td><span class="badge badge-success">Client</span></td>
                      <td>2020-10-10 14:24:24</td>
                      <td>192.168.0.1</td>
                      <td>File Upload</td>
                    </tr>
                    <tr>
                      <td>982</td>
                      <td>Rocky Doe</td>
                      <td>client@mail.com</td>
                      <td><span class="badge badge-primary">Staff</span></td>
                      <td>2020-10-10 14:24:24</td>
                      <td>192.168.0.1</td>
                      <td>File Upload</td>
                    </tr>
                  </tbody>
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