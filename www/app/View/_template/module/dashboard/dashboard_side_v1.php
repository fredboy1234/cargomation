<div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-file-alt mr-1"></i>
                  Quick Stats(Current Week)
                </h3>
                <div class="card-tools">
                  <!-- Buttons, labels, and many other things can be placed here! -->
                  <!-- Here is a label for example -->
                  <span class="badge badge-primary d-none">New</span>
                  <button type="button" class="btn btn-primary btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                </div>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                  <!-- Info Boxes Style 2 -->
                  <div class="info-box mb-3 bg-light" style="cursor:pointer;">
                    <span id="side-act-1" class="info-box-icon text-success"><i class="fas fa-box"></i></span>

                    <div class="info-box-content act-order" style="cursor: pointer;">
                      <span class="info-box-text">Active Orders</span>
                      <span class="info-box-number v1_uploaded">
                        <?php //$this->document_stats['total_files'][0]->count; ?>
                        <div class="spinner-grow spinner-grow-sm text-secondary" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                  <div class="info-box mb-3 bg-light" style="cursor:pointer;">
                    <span id="side-act-2" class="info-box-icon text-danger"><i class="fas fa-ship"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">New Shipments</span>
                      <span class="info-box-number new-shipment">
                        <?php //$this->document_stats['pending_files'][0]->count; ?>
                        <div class="spinner-grow spinner-grow-sm text-secondary" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                  <div class="info-box mb-3 bg-light" style="cursor:pointer;">
                    <span id="side-act-3" class="info-box-icon text-info"><i class="fas fa-file-alt"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Document Requests</span>
                      <span class="info-box-number requested">
                        <?php //$this->document_stats['new_request'][0]->count; ?>
                        <div class="spinner-grow spinner-grow-sm text-secondary" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                  <div class="info-box mb-3 bg-light" style="cursor:pointer;">
                    <span id="side-act-4" class="info-box-icon text-warning"><i class="fas fa-exclamation-triangle"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">ETA Updated</span>
                      <span class="info-box-number updated">
                        <?php //$this->document_stats['update_request'][0]->count; ?>
                        <div class="spinner-grow spinner-grow-sm text-secondary" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                * Notes:
              </div>
              <!-- /.card-footer -->
            </div>