<style>
    #compare-modal .modal-dialog{
        max-width: 100% !important;
        margin: 0;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100vh;
        display: flex;
    }
    #compare-modal > div > div > div.modal-body > div > div:nth-child(1){
        border-right: 5px solid #1343e6;
    }
</style>
<div class="row" style="height:420px;">
<?php 
$imglist = ['jpeg','png','jpg'];
if(!empty($this->doc_id)) {

foreach ($this->doc_id as $key => $value) {
    $file = json_decode(file_get_contents('http://'.$_SERVER['SERVER_NAME'].'/api/get/document/did/'.$value.'/name,shipment_num,type'));

    $file_name = $file[0]->name;
    $shipment_num = $file[0]->shipment_num;
    $file_type = $file[0]->type;
    $email = $this->email;

    if(!empty($this->subacc_info)) {
        $email = $this->subacc_info[0]->client_email;
    }
    $exp = explode(".",$file_name);
    $ftype = end($exp);
?>
<div class="col-md-6 embed-responsive embed-responsive-21by9">
    <?php if($ftype ==="pdf"){?>
        <iframe class="embed-responsive-item" src="<?php echo "https://docs.google.com/gview?url=https://cargomation.com/filemanager/".$email."/CW_FILE/".$shipment_num."/".$file_type."/" . $file_name."&embedded=true";?>"></iframe>
    <?php } ?>

    <?php if(in_array($ftype,$imglist)){?>
        <img src='"https://cargomation.com/filemanager/".$email."/CW_FILE/".$shipment_num."/".$file_type."/" . $file_name'/>
    <?php } ?>
</div>
<?php 
    }
}
?>
</div>