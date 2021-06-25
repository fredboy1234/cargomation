
<style>
 .vessel-container{
     cursor: pointer;
 }
 .dcontent{
    display: contents;
    color: #343a40!important;
 }
 .group a,
 .group tr{
     color:#fff !important;
 }
 /* .odd,.even{
     display: none;
 } */
 .collapse-tr{
     cursor: pointer;
 }
 .group a{
     color:#333;
 }
 .done{
    background-color: #28a745 !important;
    color: #fff !important;
 }
 .almost{
     background-color: #ffc107!important;
 }
 .completed{
     background-color: #dc3545 !important;
     color: #fff !important;
 }
 .l-done div{
     width: 10px;
     height: 10px;
     background-color:  #28a745 !important;
 }
 .l-almost div{
     width: 10px;
     height: 10px;
     background-color: #ffc107!important;
 }
 .l-completed div{
     width: 10px;
     height: 10px;
     background-color: #dc3545 !important;
 }
 .l-not-done div{
     width: 10px;
     height: 10px;
     background-color:  #fff !important;
     border: 1px solid #333;
 }
</style>
<?php $vessel = $this->vessel;?>
<section class="content">
        <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box">
              <div class="inner">
                <h3 class="text-primary">12</h3>
                <p>Confirmed Vessels</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-boat  text-primary"></i>
              </div>
              <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box">
              <div class="inner">
                <h3 class="text-success">12</h3>

                <p>Confirmed Departure</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-checkmark-outline text-success"></i>
              </div>
              <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box">
              <div class="inner">
                <h3 class="text-danger">0</h3>

                <p>Delays</p>
              </div>
              <div class="icon">
                <i class="ion ion-alert-circled text-danger"></i>
              </div>
              <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box">
              <div class="inner">
                <h3 class="text-warning">9<sup style="font-size: 20px"></sup></h3>

                <p>Pending Departure </p>
              </div>
              <div class="icon">
                <i class="ion ion-navigate text-warning"></i>
              </div>
              <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <div class="card card-default collapsed-card d-none">
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
            <div class="card-body" style="display: none;">
                <div class="tab-content2" id="">
                    <div class="tab-pane" id="vert-tabs">
                        <div class="row">
                            <div class="col-12 col-lg-2 col-md-2 col-sm-12">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-search-tab" data-toggle="pill" href="#vert-tabs-search" role="tab" aria-controls="vert-tabs-search" aria-selected="true">Advanced Search</a>
                                </div>
                            </div>
                            <div class="col-12 col-lg-10 col-md-10 col-sm-12">
                                <div class="tab-content" id="vert-tabs-tabContent">
                                    <div class="tab-pane text-left fade active show" id="vert-tabs-search" role="tabpanel" aria-labelledby="vert-tabs-search-tab">
                                        <div class="active tab-pane" id="activity">
                                            <form id="addvance-search-form">
                                                <div class="row">
                                                    <div class="col-md-12" data-select2-id="29">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Container Number:</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="inputEmail3" name="shipment_id" placeholder="Ex.: SHP001">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-2 col-form-label">Filter By:</label>
                                                            <select class="custom-select col-sm-4">
                                                            <option>Not departed from transhipment port</option>
                                                            <option>Arived at transhipment port last 6 months</option>
                                                            <option>Due to arrive transhipment port next 7 days</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6 d-inline-block">
                                                        <button id="advance-search-btn" type="button" class="btn btn-block btn-primary">Search</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="row">
                <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <div class="col-md-3 d-inline-block">
                        <h3 class="card-title d-block">List of Containers</h3><br>
                        <span>Click each container to check details.</span>
                    </div>
                    <div class="col-md-8 d-inline-block float-right">
                        <h6 class="d-inline-block">Legend:</h6>
                        <div class="l-done d-inline-block">
                            <span class="d-inline-block">Already Completed Shipment:</span><div class="d-inline-block"></div>
                        </div>
                        <div class="l-almost d-inline-block">
                        <span class="d-inline-block">Shipment Nearly Completed:</span><div class="d-inline-block"></div>
                        </div>
                        <div class="l-completed d-inline-block">
                        <span class="d-inline-block">Newly Completed Shipment:</span><div class="d-inline-block"></div>
                        </div>
                        <div class="l-not-done d-inline-block">
                        <span class="d-inline-block">Not Completed Shipment:</span><div class="d-inline-block"></div>
                        </div>
                    </div>
                    <div class="row card-body table-responsive p-0 ttable" style="height: 500px;">
                    <table class="table table-hover table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                            <th>Container Number</th>
                            <th>Vessel Name</th>
                            <th>Location</th>
                            <th>Date</th>
                            <!-- <th>Status</th> -->
                            <th>Voyage</th>
                            <th>Action</th>
                      </tr>
                    </thead>
                  </table>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
</section>
</div>
<script>
    var mapToken = <?php echo json_encode($this->mapToken);?>;
</script>