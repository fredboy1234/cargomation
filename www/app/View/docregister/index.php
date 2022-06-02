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
?>
 <style>
  .custom {
    width: 130px !important;
  }
  table.dataTable td.dt-control:before{
    height: 1em;
    width: 1em;
    margin-top: -9px;
    display: inline-block;
    color: white;
    border: 0.15em solid white;
    border-radius: 1em;
    box-shadow: 0 0 0.2em #444;
    box-sizing: content-box;
    text-align: center;
    text-indent: 0 !important;
    font-family: "Courier New",Courier,monospace;
    line-height: 1em;
    content: "+";
    background-color: #31b131;
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
                {echo "
            <option onclick='knobfunction(".$key.")' value'".$key."'>".$value."</option>";}?>
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
              <i class="fas fa-ship"></i> Shipment Count ( <code> Last <b>5 days</b>
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
            <label class="btn btn-outline-info btn-lg bg-success">
                <input type="radio" name="options" id="option_b2" autocomplete="off"> New <span class="badge bg-success">113</span>
              </label>
              <label class="btn btn-outline-info btn-lg bg-info">
                <input type="radio" name="options" id="option_b1" autocomplete="off" checked=""> Processing <span class="badge bg-info">15</span>
              </label>
              <label class="btn btn-outline-info btn-lg">
                <input type="radio" name="options" id="option_b1" autocomplete="off" checked=""> Completed <span class="badge bg-info">15</span>
              </label>
              <label class="btn btn-outline-info btn-lg bg-danger">
                <input type="radio" name="options" id="option_b3" autocomplete="off"> Failed <span class="badge bg-danger">53</span>
              </label>
              <label class="btn btn-outline-info btn-lg bg-info">
                <input type="radio" name="options" id="option_b1" autocomplete="off" checked=""> Archived <span class="badge bg-info">15</span>
              </label>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="docregister" class="table">
              <thead>
                <tr>
                  <th></th>
                  <th>Process ID</th>
                  <th>File Name</th>
                  <th>Type</th>
                  <th>Date Uploaded</th>
                  <th>Uploaded By</th>
                  <th>Actions</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
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
 <?php require_once('status.php');?>
<script>
  var user_id = '< ? = $this - > user_id; ? >' ;
  var token = " < ? = generateRandomString(); ? > ";
</script>