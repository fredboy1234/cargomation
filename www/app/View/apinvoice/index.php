<?php
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
$function_mod = array("AP Invoice", "Registration", "Compile");
$status = array("error","success");
?> <style>
  .custom {
    width: 130px !important;
  }

</style>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <!--start of row class-->
      <div class="col-sm-12 col-md-6 col-lg-6 col-6">
        <!--start of file uploader-->
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Upload new document</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="file-loading">
              <input id="invoice" name="invoice[]" type="file" class="file" accept="application/*" data-preview-file-type="text" data-allowed-file-extensions='["pdf", "docx"]'>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <button id="upload-btn" type="button" class="btn btn-block btn-outline-info btn-lg">
              <i style="font-size:15px" class="fa">&#xf085;</i>&nbsp; <b style="font-size: 15pt;">Upload</b>
            </button>
          </div>
        </div>
        <!--end of file uploader-->
      </div>
      <!--start of table-->
      <div class="col-md-6">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-ship"></i> Invoice Count ( <code> Last <b>5 days</b>
              </code>)
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div id="shipcount-chart" style="height: 475px; padding: 0px; position: relative;"></div>
          </div>
        </div>
      </div>
      <!--end of table -->
      <!--start of file table-->
      <div class="col-sm-12 col-md-6 col-lg-12 col-6">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">AP Invoice List</h3>
          </div>
          <div class="card-body">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-outline-info btn-lg">
                <input type="radio" name="options" id="option_b1" autocomplete="off" checked=""> Queue <span class="badge bg-warning">15</span>
              </label>
              <label class="btn btn-outline-info btn-lg">
                <input type="radio" name="options" id="option_b2" autocomplete="off"> Completed <span class="badge bg-success">113</span>
              </label>
              <label class="btn btn-outline-info btn-lg">
                <input type="radio" name="options" id="option_b3" autocomplete="off"> Deleted <span class="badge bg-danger">53</span>
              </label>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
              <table id="example" class="display" style="width:100%">
                  <thead id="s_headcus">
                    <?php if(!empty($this->invoicesHeader)){?>
                      <tr>
                          <th></th>
                          <?php foreach($this->invoicesHeader as $inhead){?>
                            <th><?=$inhead?></th>
                          <?php } ?>
                      </tr>
                    <?php }else{ ?>
                      <tr>
                      <th></th>
                        <th>Process ID</th>
                          <th>File Name</th>
                          <th>Job Number</th>
                          <th>Date Uploaded</th>
                          <th>Uploaded By</th>
                          <th>Action</th>
                          <th>Status</th>
                      </tr>
                    <?php } ?>
                      
                  </thead>
                  <tfoot>
                      <tr>
                          <th></th>
                          <?php foreach($this->invoicesHeader as $inhead){?>
                            <th><?=$inhead?></th>
                          <?php } ?>
                          <!-- <th>Process ID</th>
                          <th>File Name</th>
                          <th>Job Number</th>
                          <th>Date Uploaded</th>
                          <th>Uploaded By</th>
                          <th>Action</th>
                          <th>Status</th> -->
                      </tr>
                  </tfoot>
              </table>
          </div>
          
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
              <li class="page-item">
                <a class="page-link" href="#">«</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">1</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">2</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">3</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="#">»</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!--end of file table-->
    </div>
    <!--end of row class-->
  </div>
</section>
 <?php require_once('preview.php');?>
<script>
  var user_id = '< ? = $this - > user_id; ? >' ;
  var token = " < ? = generateRandomString(); ? > ";
  var param = "test";
</script>
