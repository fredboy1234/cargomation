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
$carrier = array("OOCL", "ANL", "PAE","COSCO","HAPAG LLOYD","CMA CGM","NYK");

?> <style>
  .modal.right.fade.show .modal-dialog {
    right: 0;
  }

  .modal.left .modal-dialog {
    position: fixed;
    right: 0;
    margin: auto;
    width: 1500px;
    height: 100%;
    -webkit-transform: translate3d(0%, 0, 0);
    -ms-transform: translate3d(0%, 0, 0);
    -o-transform: translate3d(0%, 0, 0);
    transform: translate3d(0%, 0, 0);
  }

  .modal.left .modal-content {
    height: 100%;
    overflow-y: auto;
  }

  .modal.right .modal-body {
    padding: 15px 15px 80px;
  }

  .modal.right.fade .modal-dialog {
    left: -320px;
    -webkit-transition: opacity 0.3s linear, left 0.3s ease-out;
    -moz-transition: opacity 0.3s linear, left 0.3s ease-out;
    -o-transition: opacity 0.3s linear, left 0.3s ease-out;
    transition: opacity 0.3s linear, left 0.3s ease-out;
  }
</style>
<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
      <!--start of row class-->
      <div class="col-sm-12 col-md-6 col-lg-6 col-6">
        <!--start of option list-->
        <div class="form-group">
          <label>Choose Module</label>
          <select class="form-control">
            <option onclick='knobfunction("~")' value=""></option> <?php foreach ($function_mod as $key => $value) 
                {echo "<option onclick='knobfunction(".$key.")' value'".$key."'>".$value."</option>";}?>
          </select>
        </div>
        <!--end of option list-->
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
              <i style="font-size:15`px" class="fa">&#xf085;</i>&nbsp; <b style="font-size: 15pt;">&nbsp;Start Training</b>
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
              <i class="far fa-chart-bar"></i> Trained Document <code> Accuracy in (%)</code>
            </h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <div id="bar-chart" style="height: 475px; padding: 0px; position: relative;"></div>
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
            <div class="col-lg-2">
              <!--start of option list-->
              <div class="form-group">
                <label>Choose Carrier</label>
                <select class="form-control">
                  <option onclick='knobfunction("~")' value=""></option> <?php foreach ($carrier as $key => $value) 
                            {echo "<option value'".$key."'>".$value."</option>";}?>
                </select>
              </div>
              <!--end of option list-->
            </div>
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
              <label class="btn btn-outline-info btn-lg">
                <input type="radio" name="options" id="option_b1" autocomplete="off" checked=""> Pending <span class="badge bg-warning">15</span>
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
                  <th>Shipment</th>
                  <th>File Name</th>
                  <th>Type</th>
                  <th>Carrier</th>
                  <th>Date Processed</th>
                  <th>Action</th>
                  <th>
                  <th>
                </tr>
              </thead>
              <tbody> <?php for ($x = 0; $x <= 5; $x++) { ?> <tr>
                  <td> <?php print_r($x); ?> </td>
                  <td>S00001234</td>
                  <td>test_staticfile <?php print_r(generateRandomString());?>.xls </td>
                  <td>civ - commercial invoice_ <?php print_r(generateRandomString());?> </td>
                  <td>N/A</td>
                  <td>2022-03-02 04:03:00PM</td>
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
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
  var user_id = < ? = $this - > user_id; ? > ;
  var token = " < ? = generateRandomString(); ? > ";
</script>