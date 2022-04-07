<!--start of modal-->
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
          <div class="row">
            <div class="col-lg-6">
             <embed src="https://cargomation.com:5002/PDF_AI/BL6227048630.pdf" style="width:100%; height:500px;" frameborder="0"></embed>
              </div>
            <div class="col-lg-2">
              <div class="card-header">
                    <h3 class="card-title">Cargowise Data</h3>
                </div>
                 <table class="table card-primary card-outline" style="overflow-x:auto;">
                    <thead>
                      <tr>
                        <th scope="col">Charge Code</th>
                        <th scope="col">Invoice #</th>
                        <th scope="col">Creditor</th>
                        <th scope="col">Invoice To</th>
                        <th scope="col">GST</th>
                        <th scope="col">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th scope="row">DOC</th>
                        <td>INV001</td>
                        <td>DUMCONSYD</td>
                        <td>Test 1</td>
                        <td>0</td>
                        <td>$130.00</td>
                      </tr>
                       <tr>
                        <th scope="row">DOC</th>
                        <td>INV001</td>
                        <td>DUMCONSYD</td>
                        <td>Test 2</td>
                        <td>0</td>
                        <td>$130.00</td>
                      </tr>
                       <tr>
                        <th scope="row">DOC</th>
                        <td>INV001</td>
                        <td>DUMCONSYD</td>
                        <td>Test 2</td>
                        <td>0</td>
                        <td>$130.00</td>
                      </tr>
                    </tbody>
                  </table>

                  <div class="card-header">
                    <h3 class="card-title">Extracted Data</h3>
                </div>
                  <table class="table card-danger card-outline" style="overflow-x:auto;">
                    <thead>
                      <tr>
                        <th scope="col">Charge Code</th>
                        <th scope="col">Invoice #</th>
                        <th scope="col">Creditor</th>
                        <th scope="col">Invoice To</th>
                        <th scope="col">GST</th>
                        <th scope="col">Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th contenteditable='true' scope="row">DOC</th>
                        <td contenteditable='true'>INV001</td>
                        <td contenteditable='true'>DUMCONSYD</td>
                        <td contenteditable='true'>Test 2</td>
                        <td contenteditable='true'>0</td>
                        <td contenteditable='true'>$130.00</td>
                      </tr>
                       <tr>
                        <th contenteditable='true' scope="row">DOC</th>
                        <td contenteditable='true'>INV001</td>
                        <td contenteditable='true'>DUMCONSYD</td>
                        <td contenteditable='true'>Test 2</td>
                        <td contenteditable='true'>0</td>
                        <td contenteditable='true'>$130.00</td>
                      </tr>
                       <tr>
                        <th contenteditable='true' scope="row">DOC</th>
                        <td contenteditable='true'>INV001</td>
                        <td contenteditable='true'>DUMCONSYD</td>
                        <td contenteditable='true'>Test 2</td>
                        <td contenteditable='true'>0</td>
                        <td contenteditable='true'>$130.00</td>
                      </tr>
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
<!--end of modal-->
