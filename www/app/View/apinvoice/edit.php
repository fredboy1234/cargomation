<style>
  .modal-footer{display: none;}
</style>
<form id="parsedForm">
  <?php foreach($this->data as $key=>$val){ ?>
    <div class="form-group">
      <label for="<?=$key?>"><?php echo $key?></label>
      <input name="<?=$key?>" type="text" class="form-control" id="<?=$key?>" aria-describedby="<?=$key?>Help" placeholder="Enter <?=$key?>" value="<?=$val?>">
      <small id="<?=$key?>Help" class="form-text text-muted"></small>
    </div>
  <?php } ?>
  <button type="button" class="btn btn-primary sendToData">Save Changes</button>
</form>
<script>
  var apinvoice = <?php echo json_encode($this->apinvoice)?>;
  var apindex = <?php echo $this->index?>;
  $(document).ready(function(){
    
  $(".sendToData").on("click",function(){
    var formData = [];

    $('#parsedForm .form-group input').each(function(){
      var tobj ={};
      var tvalue = $(this).val();
      var tname = $(this).attr("name");
      var prim_ref = $("#parsedTable_wrapper").attr("data-prim");
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
      }
    });
  });
  });
</script>