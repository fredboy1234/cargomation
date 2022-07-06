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
#loading {
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    opacity: 0.8;  
}
.spinner-border{
  position: fixed;
  left: 50%;
  top: 50%;
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
        <div class="form-group d-none">
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
              <input id="invoice" name="invoice[]" type="file" class="file" accept="application/*" data-preview-file-type="text" data-allowed-file-extensions='["pdf", "docx"]' multiple>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <button id="uploadoc" type="button" class="btn btn-block btn-outline-info btn-lg">
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
              <i class="fas fa-ship"></i> Shipment Count ( <code> This <b>Month</b>
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
            <label class="btn btn-outline-info btn-lg bg-success" id="newcount">
                <input type="radio" name="options" autocomplete="off"> New <span class="badge bg-success">0</span>
              </label>
              <label class="btn btn-outline-info btn-lg bg-info" id="processcount">
                <input type="radio" name="options"  autocomplete="off" checked=""> Processing <span class="badge bg-info">0</span>
              </label>
              <label class="btn btn-outline-info btn-lg" id="completedcount">
                <input type="radio" name="options"  autocomplete="off" checked=""> Completed <span class="badge bg-info">0</span>
              </label>
              <label class="btn btn-outline-info btn-lg bg-danger" id="failedcount">
                <input type="radio" name="options"  autocomplete="off"> Failed <span class="badge bg-danger">0</span>
              </label>
              <label class="btn btn-outline-info btn-lg bg-info" id="archivecount">
                <input type="radio" name="options"  autocomplete="off" checked=""> Archived <span class="badge bg-info">0</span>
              </label>
            </div>
            <button type="button" class=" clearall btn btn-danger float-right">Clear All</button>
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
<div class="modal fade" id="preview-doc">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Preview Shipment Data</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
         </div>
      </div>
   </div>
</div>
<div class="modal fade" id="preview-cwresponse">
   <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Preview Shipment Data</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
         </div>
      </div>
   </div>
</div>
<div id="loading" class="d-none">
<div class="spinner-border" role="status">
  <span class="sr-only">Loading...</span>
</div>
</div>
<script>
  var user_id = '< ? = $this - > user_id; ? >' ;
  var token = " < ? = generateRandomString(); ? > ";
</script>