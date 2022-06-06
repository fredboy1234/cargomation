
<!--start of modal-->
<?php 
 $hbl_numbers = $this->hbl_numbers;
 $container_details = $this->container_details;
 $doc_data = $this->doc_data;
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
   .downicon{
      position: absolute;
      bottom: -14px;
      left: 50%;
      font-size: 1.3em;
      background: #fff;
      padding: 5px;
      border-radius: 46px;
      height: 33px;
      width: 33px;
      text-align: center;
      transform: translateX(-50%);
      cursor: pointer;
      z-index: 20;
      box-shadow: 0px 1px 4px 1px rgb(0 0 0 / 30%);
   }
   #embeded{
      position: relative;
   }
   .custom_sidebar{
       width: 50%;
   }
</style>
<div class="row">
    <div class="grid-container_custom">
        <div class="custom_sidebar">
            <button onclick="customside()" id="view">
            <i class="nav-icon fas fa-exchange-alt"></i>
            </button>
            <div class="col-lg-12 sideparent">
            <div id="embeded">
                <embed src="<?=$this->filename?>" style="width:100%; height:500px;" frameborder="0"></embed>
                <a class="downicon d-none"><i class="fas fa-angle-double-down"></i></a>
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
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <?php if(!empty($hbl_numbers)){?>
                    <?php foreach($hbl_numbers as $hbl){?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">
                                <?=$hbl?>
                            </button>
                        </li>
                    <?php } ?>
                <?php } ?>
                <!-- <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">Home</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact" type="button" role="tab" aria-controls="pills-contact" aria-selected="false">Contact</button>
                </li> -->
            </ul>
            <div class="tab-content mt-3" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <div class="col-md-12">
                    <form>
                        <?php if(!empty($this->fieldlist)){ ?>
                            <?php foreach($this->fieldlist as $key=>$filedlist){ ?>
                                    <div class="form-group row d-inline-block px-2">
                                        <label for="<?=$key?>" class="col-sm-12 col-form-label col-form-label-sm"><?=$key?></label>
                                        <div class="col-sm-12">
                                            <input type="email" class="form-control form-control-sm" id="<?=$key?>" placeholder="col-form-label-sm" value="<?=$filedlist?>">
                                        </div>
                                    </div>
                            <?php } ?>
                        <?php } ?>
                        </form>
                    </div>
                    <div class="card-body" style="overflow:scroll;">
                        <table id="con-details" class="table w-100">
                            <thead>
                                <tr>
                                    <th></th>
                                    <?php if(!empty($this->tableheader)){?>
                                        <?php foreach($this->tableheader as $key=>$header){?>
                                            <th><?=$header?></th>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($container_details )){?>
                                    <?php foreach( $container_details  as $details){?>
                                        <tr>
                                            <td><i class="fas fa-edit"></i></td>
                                            <?php if(is_object($details)){?>
                                                <?php foreach($details as $det){ ?>
                                                    <td><?=$det?></td>
                                                <?php } ?>  
                                            <?php }else{ ?>
                                                <td><?=$details?></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">...</div>
                <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">...</div>
                <div class="tab-pane fade" id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab">...</div> -->
            </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5 text-center mt-3">
        <button type="button" class="btn btn-danger d-none">Raise Query</button>
        <button type="button" class="btn btn-success d-none">Tag as Validated</button>
        <button id="addtocw"  type="button" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add additional line to Cargowise." data-html="true">
            Push All
        </button>
        <button id="overidecw"  type="button" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Override matching charge codes with new data." data-html="true">
           Push Current Tab
        </button>
    </div>
</div>   
<div class="modal-footer justify-content-between">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
    
<!--end of modal-->

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
 
</script>