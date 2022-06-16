
<!--start of modal-->
<?php 
 $hbl_numbers = $this->hbl_numbers;
 $container_details = $this->container_details;
 $doc_data = $this->doc_data;
 $matchData = $this->matchData;
 $connumber = '';
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
            <?php $pdfcount=0;?>
            <?php foreach($matchData as $hbl){?>
                <?php $pdfcount++;?>
                <?php $pdfclass=$pdfcount==1 ? '' : 'd-none';?>
                <embed id="<?=$hbl['hbl_numbers']?>_embed" class="<?=$pdfclass?>" src="<?=$hbl['filename']?>" style="width:100%; height:500px;" frameborder="0"></embed>
                <a class="downicon d-none"><i class="fas fa-angle-double-down"></i></a>
            <?php } ?>
                
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
                <?php if(!empty($matchData)){?>
                    <?php $st = 0;?>
                    <?php $isact = '';?>
                    <?php foreach($matchData as $hbl){?>
                        <?php $st++; $isact=$st==1 ? 'active' :'';?>
                        <li class="nav-item <?=$isact?>" role="presentation" >
                            <a class="nav-link <?=$isact?> pdfbtn" data-embeded="<?=$hbl['hbl_numbers']?>_embed" id="<?=$hbl['hbl_numbers']?>_tab" data-toggle="tab" href="#<?=$hbl['hbl_numbers']?>_pane" role="tab" aria-controls="<?=$hbl['hbl_numbers']?>" aria-selected="true">
                                <?=$hbl['hbl_numbers']?>
                            </a>
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
                <?php $cc = 0;?>
                <?php $show = '';?>
                <?php foreach($matchData as $pkey=>$matchval){ ?>
                    <?php $cc++;?>
                    <?php $show=$cc==1 ? 'active show' : '' ;?>
                    <div class="tab-pane fade <?=$show?>" id="<?=$matchval['hbl_numbers']?>_pane" role="tabpanel" aria-labelledby="<?=$hbl['hbl_numbers']?>_tab">
                    
                        <!--List of Fields-->
                        <?php foreach($matchval['fieldlist'] as $keyField=>$listofField){?>
                            <?php if($keyField === 'Company Code'){?>
                                <div class="form-group row d-inline-block px-2" style="vertical-align: bottom;">
                                    <label for="company_code_id" class="col-sm-12 col-form-label col-form-label-sm"><?=$keyField?></label>
                                    <select id="company_code_id" class="company_code js-example-basic-single form-control form-control-sm col-sm-12" type="text">
                                        <option value="<?=$listofField?>" selected><?=$listofField?></option>
                                    </select>
                                </div>
                                <div class="form-group row d-inline-block px-2" style="vertical-align: bottom;">
                                    <label for="company_name_id" class="col-sm-12 col-form-label col-form-label-sm">Company Name</label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control form-control-sm company_name" id="company_name_id" placeholder="Company Name" value="" disabled>
                                    </div>
                                </div>
                            <?php }else{?>
                                <div class="form-group row d-inline-block px-2">
                                    <label for="<?=$keyField?>" class="col-sm-12 col-form-label col-form-label-sm"><?=$keyField?></label>
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control form-control-sm" id="<?=$keyField?>" name="<?=strtolower(str_replace(" ","_",$keyField))?>" placeholder="col-form-label-sm" value="<?=$listofField?>">
                                    </div>
                                </div>
                            <?php }?>
                        <?php } ?>
                        <div class="d-block col-md-12 " style="overflow: hidden;">
                        <button type="button" class="btn btn-primary sendToCGM float-right" data-key="<?=$pkey?>">Save Changes</button>
                        </div>
                        
                        <hr>
                        <!--Table-->
                        <div class="card-body" style="overflow:scroll;">
                            <table id="con-details" class="table w-100">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <?php foreach($matchval['tableheader'] as $theader){?>
                                            <th><?=$theader?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php if(count($matchval['container_details']) != 1){?>
                                        <?php foreach($matchval['container_details'] as $key=>$condetails){ ?>
                                            <tr>
                                            <td class="edit-details" data-tindex="<?=$key?>" data-dindex="<?=$pkey?>">
                                              <i class="fas fa-edit"></i>
                                            </td>
                                            <?php foreach($matchval['tableheader'] as $theader){?>
                                                    <?php $property_name =str_replace(" ","_",$theader);?>
                                                    <?php if(isset($condetails->$property_name)){?>
                                                        <td><?php echo $condetails->$property_name?></td>
                                                    <?php } ?>
                                            <?php } ?>
                                            </tr>
                                        <?php }?>
                                    <?php }else{?>
                                        <tr>
                                        <td class="edit-details" data-tindex="0" data-dindex="<?=$pkey?>">
                                          <i class="fas fa-edit"></i>
                                        </td>
                                            <?php foreach($matchval['container_details'] as $key=>$condetails){?>
                                                <?php foreach($condetails as $con){?>
                                                    <td><?print_r($con);//$con?></td>
                                                <?php } ?>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    
                                </tbody>
                            </table>
                        </div>
                        <!--end of table-->
                    </div>
                <?php } ?>
                
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
        <button id="addtocw" data-pid="<?=$this->process_id?>"  type="button" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Add additional line to Cargowise." data-html="true">
            Push All
        </button>
        <button id="overidecw" data-pid="<?=$this->process_id?>" type="button" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Override matching charge codes with new data." data-html="true">
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
            <h4 class="modal-title">Container Details</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p>Loading&hellip;</p>
         </div>
         <div class="modal-footer justify-content-between d-none">
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
 var match_arr = <?=json_encode($this->match_arr)?>;
 var prim_ref =<?=json_encode($this->prim_ref)?>;
 var matchjson = <?=json_encode($this->matchjson)?>;
 //show edit modal on preview doc
$(document).on('click','.edit-details',function(){
    console.log(match_arr);
    var tableindex = $(this).attr('data-tindex');
    var matcharrayIndex = $(this).attr('data-dindex');
    
    $('#edit-ap').attr("tabledindex",tableindex);
    $('#edit-ap').attr("matcharrayIndex",matcharrayIndex);
    $('#edit-ap').attr('data-prim',prim_ref);
    
    var url = "/docregister/edit?"+matcharrayIndex+'&'+tableindex+'&'+match_arr;

    $("#edit-ap .modal-body").append(loader);
    // load the url and show modal on success
    $("#edit-ap .modal-body").load(url, function (response, status, xhr) {
    if (xhr.status == 200) {
        $('#loader-wrapper').remove();
        $("#edit-ap").modal("show");
    } else {
        alert("Error: " + xhr.status + ": " + xhr.statusText);
        $('#loader-wrapper').remove();
    }
    });
});

//push edited to cgm response
$(document).on('click','.sendToCGM',function(){
        var formData = [];
        //var prim_ref = $("#edit-ap").attr("data-prim");
        var tableindex =$(this).attr("tabledindex");
        var parseindex = $(this).attr("data-key");
        $('.form-group input').each(function(){
            var tobj ={};
            var tvalue = $(this).val();
            var tname = $(this).attr("name");
            tobj[tname] = tvalue;
            formData.push(tobj);
        });
    //console.log(formData);
        $.ajax({
            url: document.location.origin+"/docregister/sendToAPI/",
            type: "POST",
            data:{
                "data": formData, 
                "docregister":matchjson, 
                "prim_ref":prim_ref,
                "tableindex":tableindex,
                "parseindex":parseindex,
                "type":'main',
            },
            success:function(data)
            {
             
               $("#edit-ap .close").trigger("click");
                Swal.fire(
                "",
                "Edit Success!",
                );
            }
        });

    });
    // $('#preview-doc').on('hidden.bs.modal', function () {
    //     $('.company_code').select2('destroy');
    // });
    $('#preview-doc').on('shown.bs.modal ', function () {
        $('.company_code').select2({
            dropdownAutoWidth : true,
            width: 'auto'
        });
        var user_id = <?=json_encode($this->userid)?>;
        $.ajax({
            url: document.location.origin+"/docregister/getOrgCodeByUserID/",
            success:function(data)
            {
                $.each( JSON.parse(data), function( key, value ) {
                    console.log(value);
                if(value.consignee !== '') {
                    console.log(value.consignee);
                    var newOption = new Option(value.consignee, value.company_name, false, false);
                    console.log(newOption);
                    $('.company_code').append(newOption).trigger('change');
                }
                });
            }
        });
    });
    
    $('.company_code').select2().on('change', function(){
        var text = $(this).val();
        if(text == null){
            text = 'Not Specified';
        }
        $('.company_name').val(text);
	});
    
</script>