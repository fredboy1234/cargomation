<form id="update_info" class="form-horizontal" _lpchecked="1">
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">First Name</label>

        <div class="col-sm-10">
        <input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?=$this->contact_info->first_name?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Last Name</label>

        <div class="col-sm-10">
        <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?=$this->contact_info->last_name?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Address</label>

        <div class="col-sm-10">
        <input type="text" name="address" class="form-control" placeholder="Address" value="<?=$this->contact_info->address?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">City</label>

        <div class="col-sm-10">
        <input type="text" name="city" class="form-control" placeholder="City" value="<?=$this->contact_info->city?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Zipcode</label>

        <div class="col-sm-10">
        <input type="text" name="postcode" class="form-control" placeholder="Zipcode" value="<?=$this->contact_info->postcode?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Contact No.</label>

        <div class="col-sm-10">
        <input type="text" name="phone" class="form-control" placeholder="Contact" value="<?=$this->contact_info->phone?>">
        </div>
    </div>
    
    <?php if(false): ?>
    <div class="form-group">
        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

        <div class="col-sm-10">
        <input disabled="disabled" type="email" name="email" class="form-control" placeholder="Email" value="<?=$this->contact_info->email?>">
        </div>
    </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="user_id" value="<?=$this->contact_info->user_id; ?>">
        <button type="submit" class="btn btn-danger">Submit</button>
        </div>
    </div>
</form>

<script>
$(document).ready(function () {

    $('form#update_info').submit(function(event) {
        var loader = '<div id="spinner" class="d-flex justify-content-center">' +
                    '<div class="spinner-border" role="status">' + 
                    ' <span class="sr-only">Loading...</span>' + 
                    '</div>' +
                    '</div>';
       
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: document.location.origin + "/contact/update/<?= $this->contact_info->id; ?>",
            data: $(this).serializeArray(),
            dataType: "JSON",
            beforeSend: function () {
                $('form#update_info > div').hide();
                $('form#update_info').append(loader);
            },
            success: function (res) {
                console.log(res);
                if(!res) {
                    $('#spinner').remove();
                    $('form#update_info > div').remove();
                    $('form#update_info').html('Successfully updated');
                } else {
                    console.log(res);
                }
            },
            error: function (res) {
                console.log(res);
            }
        });
    });

});

</script>