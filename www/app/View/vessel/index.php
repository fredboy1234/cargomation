
<?php
$table_header=array("Container","BOL","Carrier","Arrival@POD","POL","POD","Path","Status","Current Vessel","Carrier ETA");
?>
<style>
 .mismatch,
 .mismatchvsl,
 .mismatchVo,
 .mismatchLocETD{
  position: absolute;
    border: 1px solid #3333;
    padding: 0.4rem;
    border-radius: 8px;
    background: #fff;
    z-index: 9999 !important;
    
 }
 #DataTables_Table_0 > tbody > tr > td:nth-child(4){
   width: 10%!important;
 }
 .mismatch{
    display: block;
    top: -20px;
    margin-left: 200px;
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
          <!--Box 1-->
          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
              <span class="info-box-icon bg-info elevation-1"><i class="fas fa-ship"></i></span>
              <div class="info-box-content">
                <span class="info-box-text"><h5>Confirmed Vessels</h5></span>
                <span class="info-box-number">503</span>
                </div>
            </div>
          </div>

          <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-double"></i></span>
              <div class="info-box-content">
              <span class="info-box-text"><h5>Confirmed Departure</h5></span>
              <span class="info-box-number">41,410</span>
              </div>
            </div>
          </div>

          <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
              <div class="info-box mb-3">
              <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clock"></i></span>
              <div class="info-box-content">
              <span class="info-box-text"><h5>Delays</h5></span>
              <span class="info-box-number">760</span>
              </div>
              </div>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-hourglass"></i></span>
            <div class="info-box-content">
            <span class="info-box-text"><h5>Pending Transhipment</h5></span>
            <span class="info-box-number">2000</span>
            </div>
            </div>
        </div>


        </div> 
        <div class="card card-default collapsed-card f">
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
            <div class="card-body">
                <div class="tab-content2" id="">
                    <div class="tab-pane" id="vert-tabs">
                        <div class="row">
                            <div class="col-12 col-lg-2 col-md-2 col-sm-12">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-search-tab" data-toggle="pill" href="#vert-tabs-search" role="tab" aria-controls="vert-tabs-search" aria-selected="true">Advanced Search</a>
                                    <a class="nav-link" id="vert-tabs-save-tab" data-toggle="pill" href="#vert-tabs-save" role="tab" aria-controls="vert-tabs-save" aria-selected="false">Save/Recent Search List</a>
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
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-save" role="tabpanel" aria-labelledby="vert-tabs-save-tab">
                                        <div class="tab-pane" id="save">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Save Search</label>
                                                        <select id="save_search" multiple="" class="form-control">
                                                        </select>
                                                    </div>
                                                </div>
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
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mt-2">
                                                    <button type="button" id="resetSearch" class="btn btn-warning">
                                                        <i class="fas fa-undo"></i> Reset Selection
                                                    </button>
                                                    <button type="button" id="deleteSearch" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i> Delete Query
                                                    </button>
                                                </div>
                                                <div class="col-md-6 mt-2 text-right">
                                                    <button type="button" id="loadSearch" class="btn btn-success">
                                                        <i class="fas fa-search"></i> Load Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <div class="row">
            <div class="col-lg-2">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Quick Search</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
                </button>
              </div>
          </div>

          <div class="card-body p-0">
              <form method="POST" action="#">
              <ul class="nav nav-pills flex-column">
                <li class="nav-item active">
                <a href="#" class="nav-link">
                <i class="fas fa-ship"></i> Shipment
                <input class="form-control form-control-sm" type="text" placeholder="">
                </a>
              </li>

              <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="fas fa-box"></i> Container
                <input class="form-control form-control-sm" type="text" placeholder="">
                </a>
              </li>

               <li class="nav-item">
                <a href="#" class="nav-link">
                <i class="fas fa-file"></i> Mbill
                <input class="form-control form-control-sm" type="text" placeholder="">
                </a>
              </li>

               <li>
                <a href="#" class="nav-link">
                <i class="fas fa-ship"></i> Voyage
                <input class="form-control form-control-sm" type="text" placeholder="">
                </a>
              </li>

              <li>
                <a href="#" class="nav-link">
                <i class="fas fa-calendar"></i> ETA
                <input class="form-control form-control-sm" type="date" placeholder="">
                </a>
              </li>

              <li>
                <a href="#" class="nav-link">
                <i class="fas fa-calendar"></i> ETD
                <input class="form-control form-control-sm" type="date" placeholder="">
                </a>
              </li>

              <li>
              <div class="btn-group">
                <a href="#" class="nav-link">
                <button class="btn btn-info">
                    <i class="icon-in-button"></i>  
                    Search
                </button>
                <button class="btn btn-danger">
                    <i class="icon-in-button"></i>  
                    Clear
                </button>
                <button class="btn btn-success">
                    <i class="icon-in-button"></i>  
                    Save Search
                </button>
              </a>
            </div>
          </li>

              </ul>
             </form>
          </div>
          </div>
        </div>
          <!--start of order table-->
          <div class="col-lg-10">
            <div class="card card-primary card-outline card-tabs">
              <div class="card-header p-0 pt-1 border-bottom-0">
                
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade active show" id="custom-tabs-sea" role="tabpanel" aria-labelledby="custom-tabs-sea-tab">
                     <table id="myTable1" class="table table-striped table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <?php foreach ($table_header as $key => $value) { ?>
                                    <th><?php echo $value;?></th>
                                    <?php }?>
                                </tr>
                            </thead>
                        </table>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!--end of order table-->

        </div><!--end of row class-->
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
    var user_id = <?php echo $this->user_id ?>;
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