<div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="far fa-file-alt mr-1"></i>
                  Document Stats
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
                  <div class="info-box mb-3 bg-light">
                    <span class="info-box-icon text-success"><i class="far fa-thumbs-up"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Total Uploaded Files</span>
                      <span class="info-box-number uploaded">
                        <?php //$this->document_stats['total_files'][0]->count; ?>
                        <div class="spinner-grow spinner-grow-sm text-secondary" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                  <div class="info-box mb-3 bg-light">
                    <span class="info-box-icon text-danger"><i class="fas fa-exclamation-triangle"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Document for Approval</span>
                      <span class="info-box-number approval">
                        <?php //$this->document_stats['pending_files'][0]->count; ?>
                        <div class="spinner-grow spinner-grow-sm text-secondary" role="status">
                          <span class="sr-only">Loading...</span>
                        </div>
                      </span>
                    </div>
                    <!-- /.info-box-content -->
                  </div>
                  <!-- /.info-box -->
                  <div class="info-box mb-3 bg-light">
                    <span class="info-box-icon text-info"><i class="far fa-check-square"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Requested Documents</span>
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
                  <div class="info-box mb-3 bg-light">
                    <span class="info-box-icon text-warning"><i class="far fa-edit"></i></span>

                    <div class="info-box-content">
                      <span class="info-box-text">Document for Update</span>
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