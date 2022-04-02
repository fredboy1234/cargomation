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
            <button type="button" class="btn btn-block btn-outline-info btn-lg">
              <i style="font-size:15`px" class="fa">&#xf085;</i>&nbsp; <b style="font-size: 15pt;">Upload</b>
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
            <h3 class="card-title">Upload List</h3>
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
            <table class="table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>File Name</th>
                  <th>Type</th>
                  <th>Date Uploaded</th>
                  <th>Preview</th>
                  <th>Actions</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody> <?php for ($x = 0; $x <= 5; $x++) { ?> <tr>
                  <td> <?php print_r($x); ?> </td>
                  <td>AP_Inv <?php print_r(generateRandomString());?>.xls </td>
                  <td>AP_Inv <?php print_r(generateRandomString());?> </td>
                  <td>2022-03-02 04:03:00PM</td>
                  <td><button type="button" class="btn btn-block btn-outline-primary" data-toggle='modal' data-target='#modal-lg-prev'>Preview Parsed</button></td>
                  <td>
                    <div class="btn-group">
                      <button type="button" class="btn btn-default">Action</button>
                      <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown" aria-expanded="false">
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <div class="dropdown-menu" role="menu" style="">
                        <a class="dropdown-item" href="#">Push to Cargowise</a>
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal-xl">View File</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Delete</a>
                      </div>
                    </div>
                  </td>
                  <td><small class="badge badge-warning">Queue</small></td>
                </tr> <?php } ?> </tbody>
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
  var user_id = < ? = $this - > user_id; ? > ;
  var token = " < ? = generateRandomString(); ? > ";
</script>
