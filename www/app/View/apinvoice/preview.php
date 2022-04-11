<!--start of modal-->
<style>
#modal-lg-prev .modal-dialog{
  max-width: 100% !important;
  margin: 0;
  top: 0;
  bottom: 0;
  left: 0;
  right: 0;
  height: 100vh;
  display: flex;
}
</style>
<div class="modal fade" id="modal-lg-prev">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Preview Charges</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div class="row" style="overflow: scroll;">
            <div class="col-lg-5">
             <embed src="https://cargomation.com:5002/PDF_AI/BL6227048630.pdf" style="width:100%; height:500px;" frameborder="0"></embed>
              </div>
            <div class="col-lg-7">
              <div class="card-header">
                    <h3 class="card-title">Cargowise Data</h3>
                </div>
                 <table id="headerTable" class="table card-primary card-outline" style="overflow-x:auto;">
                    <thead>
                      <tr>
                        <?php foreach($this->headerMatched as $hval){ ?>
                        <th scope="col"><?php echo $hval?></th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                  </table>

                  <div class="card-header">
                    <h3 class="card-title">Extracted Data</h3>
                </div>
                  <table id="parsedTable" class="table card-danger card-outline" style="overflow-x:auto;">
                    <thead>
                      <tr>
                        <?php foreach($this->headerParsed as $parsehead){?>
                          <th scope="col"><?php echo $parsehead?></th>
                        <?php } ?>
                         <th></th>
                      </tr>
                    </thead>
                    <tbody>
          
                    </tbody>
                  </table>
            </div>
            <div class="col-lg-8">
              <button type="button" class="btn btn-danger">Raise Query</button>
              <button type="button" class="btn btn-success">Tag as Validated</button>
              <button type="button" class="btn btn-info">Add to CW1</button>
              <button type="button" class="btn btn-info">Update to CW1</button>
              <button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>
            </div>
          </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!--end of modal-->


<!--start of modal-->
<div class="modal fade" id="modal-lg-success">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Discrepancy</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Discrepancy Field</th>
              <th scope="col">MBL</th>
              <th scope="col">HBL</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">1</th>
              <td>Package Count</td>
              <td>test</td>
              <td>test data</td>
            </tr>
            <tr>
              <th scope="row">2</th>
              <td>Container</td>
              <td>test</td>
              <td>test data</td>
            </tr>
            <tr>
              <th scope="row">3</th>
              <td>Test Error</td>
              <td>test</td>
              <td>test data</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success">Preview Shipment/Consol Details</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="edit-ap">
  <div class="modal-dialog modal-lg" style="width:100%; max-width:1088px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Document</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Loading&hellip;</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!--end of modal-->
<script>
  var parsedData = <?php echo $this->parsedData?>;
  var apinvoice = <?php echo json_encode($this->apinvoice)?>;
</script>