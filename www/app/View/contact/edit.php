<?php

?>

<form class="form-horizontal" _lpchecked="1">
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">First Name</label>

        <div class="col-sm-10">
        <input type="text" name="firstname" class="form-control" id="inputName" placeholder="First Name" value="<?=$this->contact_info->first_name?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Last Name</label>

        <div class="col-sm-10">
        <input type="text" name="lastname" class="form-control" id="inputName" placeholder="Last Name" value="<?=$this->contact_info->last_name?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Address</label>

        <div class="col-sm-10">
        <input type="text" name="address" class="form-control" id="inputName" placeholder="Address" value="<?=$this->contact_info->address?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">City</label>

        <div class="col-sm-10">
        <input type="text" name="city" class="form-control" id="inputName" placeholder="City" value="<?=$this->contact_info->city?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Zipcode</label>

        <div class="col-sm-10">
        <input type="text" name="zipcode" class="form-control" id="inputName" placeholder="Zipcode" value="<?=$this->contact_info->postcode?>">
        </div>
    </div>
    <div class="form-group">
        <label for="inputName" class="col-sm-2 control-label">Contact No.</label>

        <div class="col-sm-10">
        <input type="text" name="contact" class="form-control" id="inputName" placeholder="Contact" value="<?=$this->contact_info->phone?>">
        </div>
    </div>
    
    <?php if(false): ?>
    <div class="form-group">
        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

        <div class="col-sm-10">
        <input disabled="disabled" type="email" name="email" class="form-control" id="inputEmail" placeholder="Email" value="<?=$this->contact_info->email?>">
        </div>
    </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
        <input type="hidden" name="info_id" value="<?=$this->contact_info->id?>">
        <button type="submit" class="btn btn-danger">Submit</button>
        </div>
    </div>
</form>