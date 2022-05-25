<style>
  .modal-footer{display: none;}
</style>
<?php $invoiceDate = ''; ?>
<?php $disabledInput = array("InvoiceNumber","ExchangeRate");?>
<form id="parsedForm">
  <?php foreach($this->data as $key=>$val){ $disable="";?>
    <?php
      if(in_array($key,$disabledInput)){
        $disable = "disabled";
      }
      if($key==='InvoiceDate'){
        $invoiceDate = $val;
      }
    ?>
    <div class="form-group">
      <label for="<?=$key?>"><?php echo $key?></label>
      <input name="<?=$key?>" type="text" class="form-control" id="<?=$key?>" aria-describedby="<?=$key?>Help" placeholder="Enter <?=$key?>" value="<?=$val?>"  <?=$disable?>>
      <small id="<?=$key?>Help" class="form-text text-muted"></small>
    </div>
  <?php } ?>
  <button type="button" class="btn btn-primary sendToData">Save Changes</button>
</form>
<script>
  var apinvoice = <?php echo json_encode($this->apinvoice)?>;
  var apindex = <?php echo $this->index?>;
  $(document).ready(function(){
  
  $('#ExchangeRate, #SubTotal, #GST, .Discrepancy').keypress(function (e) {    
    var charCode = (e.which) ? e.which : event.keyCode;
    if (String.fromCharCode(charCode).match(/[^0-9\.]/g))
      return false;
  });  
  
  $(".sendToData").on("click",function(){
    var formData = [];
    var prim_ref = $("#parsedTable_wrapper").attr("data-prim");
    
    $('#parsedForm .form-group input').each(function(){
      var tobj ={};
      var tvalue = $(this).val();
      var tname = $(this).attr("name");
      tobj[tname] = tvalue;
      formData.push(tobj);
    });
    
    $.ajax({
      url: document.location.origin+"/apinvoice/sendToAPI/",
      type: "POST",
      data:{"data": formData, "apinvoice":apinvoice, "index":apindex,"prim_ref":prim_ref},
      success:function(data)
      {
        console.log(data);
        $("#edit-ap .close").trigger("click");
        Swal.fire(
          "",
          "Edit Success!",
          );
      }
    });
  });

  const monthNames = ["January", "February", "March", "April", "May", "June",
  "July", "August", "September", "October", "November", "December"
  ];
  var invoiceDate = "<?=$invoiceDate?>";
  var formatedInvoiceDate = "";
  if(invoiceDate !== ""){
    var d = new Date(invoiceDate);
    
    formatedInvoiceDate = `${d.getMonth()}/${d.getDate()}/${d.getFullYear()}`;
  }
  $('input[name="InvoiceDate"]').daterangepicker({
    "singleDatePicker": true,
    "startDate": d,
    
    locale: {
      format: 'MMM D, YYYY'
    }
  }, function(start, end, label) {
    console.log('New date range selected: ' + start.format('dd/mm/yyyy'));
  });

  });
</script>