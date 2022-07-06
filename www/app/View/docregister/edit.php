<form id="parsedForm">
    <?php foreach($this->tableData as $key=>$val){ $disable="";?>
    <div class="form-group">
    <label for="<?=$key?>"><?php echo $key?></label>
    <input name="<?=$key?>" type="text" class="form-control" id="<?=$key?>" aria-describedby="<?=$key?>Help" placeholder="Enter <?=$key?>" value="<?=$val?>"  <?=$disable?>>
      <small id="<?=$key?>Help" class="form-text text-muted"></small>
    </div>
    <?php } ?>
    <button type="button" class="btn btn-primary sendToData">Save Changes</button>
</form>
<script>
    var docreg = <?=json_encode($this->data)?>;
    $(".sendToData").on('click',function(){
        var formData = [];
        var prim_ref = $("#edit-ap").attr("data-prim");
        var tableindex =$("#edit-ap").attr("tabledindex");
        var parseindex = $("#edit-ap").attr("matcharrayindex");
        $('#edit-ap .form-group input').each(function(){
            var tobj ={};
            var tvalue = $(this).val();
            var tname = $(this).attr("name");
            tobj[tname] = tvalue;
            formData.push(tobj);
        });
    
        $.ajax({
            url: document.location.origin+"/docregister/sendToAPI/",
            type: "POST",
            data:{
                "data": formData, 
                "docregister":docreg, 
                "prim_ref":prim_ref,
                "tableindex":tableindex,
                "parseindex":parseindex,
                "type":'table',
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
</script>