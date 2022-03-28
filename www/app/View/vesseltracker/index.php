<?php
$table_header=array("Container","BOL","Carrier","Arrival@POD","POL","POD","Path","Status","Current Vessel","Carrier ETA");
?>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row"><!--start of row class-->
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
          <!--start of column filter-->
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

     <!-- The Modal -->
  <div class="modal" id="exampleModal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
         <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Live Tracking</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>        
            <!-- Modal body -->
            <div class="modal-body">
              <?php require_once('mapinfo.php'); ?>
            </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
        
      </div>
    </div>
  </div>
</section>
</div>
<script>  
var user_id = <?= $this->user_id; ?>;
</script>