
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
</style>
<?php $vessel = $this->vessel;?>
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
                    <h3 class="card-title d-block">List of Containers</h3><br>
                    <span>Click each container to check details.</span>
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