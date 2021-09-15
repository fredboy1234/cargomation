
<style>

 .mismatch{
  position: absolute;
    border: 1px solid #3333;
    padding: 0.4rem;
    border-radius: 8px;
    background: #fff;
    z-index: 9999 !important;
 }
 
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
 
 tr.done{
    /* background-color: #28a745 !important;
    color: #fff !important; */
    border-top: 2px solid #28a745 !important;
    transform: scaleX(1);
 }
 tr.done:after {
  display:block;
  content: '';
  border-top: solid 1.4px #28a745;  
  transform: scaleX(0);  
  transition: transform 550ms ease-in-out;
}
tr.done:hover:after { transform: scaleX(1); }
tr.done.fromRight:after{ transform-origin:100% 50%; }
tr.done.fromLeft:after{  transform-origin:  0% 50%; }

 tr.almost{
     /* background-color: #ffc107!important; */
     border-top: 2px solid #ffc107!important;
 }
 tr.almost:after {
  display:block;
  content: '';
  border-top: solid 1.4px #ffc107;  
  transform: scaleX(0);  
  transition: transform 550ms ease-in-out;
}
tr.almost:hover:after { transform: scaleX(1); }
tr.almost.fromRight:after{ transform-origin:100% 50%; }
tr.almost.fromLeft:after{  transform-origin:  0% 50%; }

 tr.completed{
     /* background-color: #dc3545 !important;
     color: #fff !important; */
     border-top: 2px solid #dc3545 !important;
 }
 
 tr.completed:after {
  display:block;
  content: '';
  border-top: solid 1.4px #dc3545;  
  transform: scaleX(0);  
  transition: transform 550ms ease-in-out;
}
tr.completed:hover:after { transform: scaleX(1); }
tr.completed.fromRight:after{ transform-origin:100% 50%; }
tr.completed.fromLeft:after{  transform-origin:  0% 50%; }

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
 .custom-table thead tr{
   line-height: 1rem;
 }
 .custom-table tbody tr td{
   border: none !important;
   /* background: #fff !important; */
   display: table-cell;
    width: 12%;
 }
 .custom-table td a, table tbody tr td{
  color: #777;
    font-weight: 400 !important;
    padding-bottom: 20px !important;
    padding-top: 20px !important;
    font-weight: 300 !important;
 }
 .custom-table{
    /* display: grid;  */
    border-collapse: collapse !important;
    border-spacing: 0.4rem !important;
 }
 .custom-table tbody{
    /* display: contents; */
    display: grid;
    width: 100%;
    position: absolute;
 }
 .custom-table tbody tr{
  border-radius: 7px;
  -webkit-transition: .3s all ease;
  -o-transition: .3s all ease;
  transition: .3s all ease;
  margin: 0.6rem;
  box-shadow: rgb(0 0 0 / 16%) 0px 1px 4px;
  /* z-index: 100 !important; */
 }
 #DataTables_Table_0_wrapper{
   display: table;
 }

</style>
<?php $vessel = $this->vessel;?>
<section class="content">
        <div class="container-fluid">
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box box-search" id="confirmedvessels">
              <div class="inner">
                <h3 class="text-primary"><?= $this->confirmed;?></h3>
                <p>Confirmed Vessels</p>
              </div>
              <div class="icon">
                <i class="ion ion-android-boat  text-primary"></i>
              </div>
              <a href="#" class="small-box-footer text-dark d-none">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box box-search" id="departure">
              <div class="inner">
                <h3 class="text-success"><?= $this->departCOnfirmed;?></h3>

                <p>Confirmed Departure</p>
              </div>
              <div class="icon">
                <i class="ion ion-ios-checkmark-outline text-success"></i>
              </div>
              <a href="#" class="small-box-footer text-dark d-none">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box box-search" id="delay">
              <div class="inner">
                <h3 class="text-danger"><?= $this->delay;?></h3>

                <p>Delays</p>
              </div>
              <div class="icon">
                <i class="ion ion-alert-circled text-danger"></i>
              </div>
              <a href="#" class="small-box-footer text-dark d-none">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box box-search" id="pending">
              <div class="inner">
                <h3 class="text-warning"><?= $this->pending;?><sup style="font-size: 20px"></sup></h3>

                <p>Pending Departure </p>
              </div>
              <div class="icon">
                <i class="ion ion-navigate text-warning"></i>
              </div>
              <a href="#" class="small-box-footer text-dark d-none">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <div class="card card-default collapsed-card d-none ">
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
                                                    <div class="col-md-6" data-select2-id="29">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Container Number:</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="inputEmail3" name="shipment_id" placeholder="Ex.: SHP001">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" data-select2-id="29">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Container Number:</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="inputEmail3" name="shipment_id" placeholder="Ex.: SHP001">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" data-select2-id="29">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Container Number:</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="inputEmail3" name="shipment_id" placeholder="Ex.: SHP001">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" data-select2-id="29">
                                                        <div class="form-group row">
                                                            <label for="inputEmail3" class="col-sm-2 col-form-label">Container Number:</label>
                                                            <div class="col-sm-4">
                                                                <input type="text" class="form-control" id="inputEmail3" name="shipment_id" placeholder="Ex.: SHP001">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4 d-inline-block">
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
                    <div class="col-md-8  float-right d-none">
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
                    <table class="table table-hover table-head-fixed text-nowrap custom-table">
                    <thead>
                      <tr>
                            <th scope="col" class="btn-search">Container Number <section></section></th>
                            <th scope="col">Shipment Number<section></section></th>
                            <!-- <th>House Bill <section></section></th> -->
                            <th scope="col">Master Bill <section></section></th>
                            <th scope="col">Voyage <section></section></th>
                           
                            <th scope="col">Date <section></section></th>
                            <!-- <th>Status</th> -->
                            
                            <th scope="col">Vessel Name <section></section></th>
                            <th scope="col">Location <section></section></th>
                            <th scope="col">1 Stop <section></section></th>
                            
                            <th scope="col">Shipping Line <section></section></th>
                            
                            <th scope="col">Action <section></section></th>
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
<div class="modal fade" id="detailinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Movements Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
    var mapToken = <?php echo json_encode($this->mapToken);?>;
    var searates = <?php echo json_encode($this->sea_rates)?>;
    var doublechecker =<?php echo json_encode($this->doublechecker)?> ;
    var statsCode = {
        UNK : 'Unknown',
        LTS : 'Land transshipment',
        BTS : 'Barge transshipment',
        CEP : 'Container empty to shipper',
        CPS : 'Container pickup at shipper',
        CGI : 'Container arrival at first POL (Gate in)',
        CLL : 'Container loaded at first POL',
        VDL : 'Vessel departure from first POL',
        VAT : 'Vessel arrival at T/S port',
        CDT : 'Container discharge at T/S port',
        TSD : 'Transshipment Delay',
        CLT : 'Container loaded at T/S port',
        VDT : 'Vessel departure from T/S',
        VAD : 'Vessel arrival at final POD',
        CDD : 'Container discharge at final POD',
        CGO : 'Container departure from final POD (Gate out)',
        CDC : 'Container delivery to consignee',
        CER : 'Container empty return to depot',
    };
    var onestopLoc = {
      Sydney : 'SYD',
      Melbourne :  'MEL',
      Brisbane : 'BNE',
      Adelaide : 'ADL',
      Fremantle : 'FRE',
      Auck: 'AKL'
    };
</script>