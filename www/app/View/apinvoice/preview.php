
<!--start of modal-->
<?php 
 $pdfparsed = json_decode($this->parsedData);
 $jnumber = '';
 if(isset($pdfparsed->ParsedPDFHeader->JobNumber)){
   $jnumber = $pdfparsed->ParsedPDFHeader->JobNumber;
 }
?>
<style>
   .loaderstyle{
      position: absolute;
    top: 13%;
    right: 50%;
   }
   #headerTable_wrapper{
      overflow: hidden;
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
            <div class="row">
               <div class="grid-container_custom">
                  <div class="custom_sidebar">
                     <button onclick="customside()" id="view">
                     <i class="nav-icon fas fa-exchange-alt"></i>
                     </button>
                     <div class="col-lg-12">
                        <div id="embeded">
                           <embed src="" style="width:100%; height:500px;" frameborder="0"></embed>
                        </div>
                        <div id="infor-boxes">
                           <!-- <div id="cusdiv" class="danger">
                              <p><strong>Discrepancy Found: </strong> Some text...</p>
                           </div>
                           <div id="cusdiv" class="success">
                              <p><strong>Job # Matched: </strong> Some text...</p>
                           </div>
                           <div id="cusdiv" class="info_1">
                              <p><strong>Charge code Matched: </strong> Some text...</p>
                           </div>
                           <div id="cusdiv" class="warning">
                              <p><strong>Warning: </strong> Some text...</p>
                           </div> -->
                        </div>
                     </div>
                  </div>
                  <div class="main-content_custom">
                     <div class="col-lg-12">
                        <div class="card-header">
                           <h3 class="card-title">Cargowise Data</h3>
                           <a class="macro float-right" href="javascript:void(0);" >
                              <h3 style="cursor: pointer;" class="card-title float-right jobtooltip" data-toggle="tooltip" data-placement="top" title="" data-original-title="" data-html="true" >
                                 JOB Number: 
                                 <span class="jobnum"></span>
                              </h3>
                           </a>
                           
                        </div>
                        <?php if(!empty($this->headerMatched)){?>
                        <table style="width:100%" id="headerTable" class="table card-primary card-outline">
                           <thead>
                              <tr>
                                 <?php foreach($this->headerMatched as $matchVal){?>
                                 <th scope="col"><?php echo $matchVal?></th>
                                 <?php } ?>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                        <?php } ?>
                        <div class="card-header">
                           <h3 class="card-title">Extracted Data</h3>
                        </div>
                        <?php if(!empty($this->headerParsed)){?>
                        <table style="width:100%" id="parsedTable" class="table card-danger card-outline">
                           <thead>
                              <tr>
                                 <th></th>
                                 <?php foreach($this->headerParsed as $headparsed){?>
                                 <th scope="col"><?php echo $headparsed ?></th>
                                 <?php } ?>
                              </tr>
                           </thead>
                           <tbody>
                           </tbody>
                        </table>
                        <?php } ?>
                     </div>
                  </div>
               </div>
               <div class="col-lg-5 text-center">
                  <button type="button" class="btn btn-danger d-none">Raise Query</button>
                  <button type="button" class="btn btn-success d-none">Tag as Validated</button>
                  <button id="addtocw"  type="button" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add additional line to Cargowise." data-html="true">
                     Push to Cargowise
                  </button>
                  <button id="overidecw"  type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="" data-original-title="Override matching charge codes with new data." data-html="true">
                     Update Cargowise
                  </button>
                  <button id="apportioncw"  type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add charges to Consol Costing for multiple Shipments." data-html="true">
                     Apportion Charges 
                  </button>
                  <button type="button" class="btn btn-info d-none">Update to CW1</button>
                  <button type="button" class="btn btn-warning d-none" data-dismiss="modal">Cancel</button>
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
            <h4 class="modal-title">Invoice</h4>
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

