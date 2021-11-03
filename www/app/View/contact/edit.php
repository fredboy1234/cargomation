<form id="update_info" class="mx-5 form-horizontal" _lpchecked="1">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputName" class="control-label">First Name</label>
            <input type="text" name="first_name" class="form-control" placeholder="First Name" value="<?=$this->contact_info->first_name?>">
        </div>
        <div class="form-group col-md-6">
            <label for="inputName" class="control-label">Last Name</label>
            <input type="text" name="last_name" class="form-control" placeholder="Last Name" value="<?=$this->contact_info->last_name?>">

        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="control-label">Address</label>
        <input type="text" name="address" class="form-control" placeholder="Address" value="<?=$this->contact_info->address?>">
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputName" class="control-label">City</label>
            <input type="text" name="city" class="form-control" placeholder="City" value="<?=$this->contact_info->city?>">
        </div>
        <div class="form-group col-md-4">
            <label for="inputState">State</label>
                <select id="inputState" class="form-control" disabled>
                <option selected>Choose...</option>
                <option>...</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="inputName" class="control-label">Zipcode</label>
            <input type="text" name="postcode" class="form-control" placeholder="Zipcode" value="<?=$this->contact_info->postcode?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="control-label">Contact No.</label>
        <input type="text" name="phone" class="form-control" placeholder="Contact" value="<?=$this->contact_info->phone?>">
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
        <div class="col-sm-offset-2">
        <input type="hidden" name="user_id" value="<?=$this->contact_info->uid; ?>">
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
            url: document.location.origin + "/contact/update/<?= $this->contact_info->ucid; ?>",
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