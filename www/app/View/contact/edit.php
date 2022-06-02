<?php 
$document_settings = $selected_doc = [];
if(!is_null(json_decode($this->document_type[0]->shipment))) {
    $document_settings = json_decode($this->document_type[0]->shipment);
    foreach ($document_settings as $key => $value) {
        if($value->index_lvl == 'document') {
            $selected_doc[] = strtoupper($value->index);
        }
    }
}

?>
<div class="row">
    <div class="col-5 col-sm-3">
        <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
            <a class="nav-link active" id="vert-tabs-info-tab" data-toggle="pill" href="#vert-tabs-info" role="tab" aria-controls="vert-tabs-info" aria-selected="true">Information</a>
            <a class="nav-link" id="vert-tabs-shipment-tab" data-toggle="pill" href="#vert-tabs-shipment" role="tab" aria-controls="vert-tabs-shipment" aria-selected="false">Shipment</a>
            <a class="nav-link" id="vert-tabs-document-tab" data-toggle="pill" href="#vert-tabs-document" role="tab" aria-controls="vert-tabs-document" aria-selected="false">Document</a>
            <a class="nav-link" id="vert-tabs-general-tab" data-toggle="pill" href="#vert-tabs-general" role="tab" aria-controls="vert-tabs-general" aria-selected="false">Settings</a>
        </div>
    </div>
    <div class="col-7 col-sm-9">
        <div class="tab-content" id="vert-tabs-tabContent">
            <div class="tab-pane text-left fade active show" id="vert-tabs-info" role="tabpanel" aria-labelledby="vert-tabs-info-tab">
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
                    <div class="form-group">
                        <div class="col-sm-offset-2">
                        <input type="hidden" name="user_id" value="<?=$this->contact_info->uid; ?>">
                        <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="vert-tabs-shipment" role="tabpanel" aria-labelledby="vert-tabs-shipment-tab">
                Coming soon...
            </div>
            <div class="tab-pane fade" id="vert-tabs-document" role="tabpanel" aria-labelledby="vert-tabs-document-tab">
                <form id="document_type" class="mx-5 form-horizontal" _lpchecked="1">
                    <?php if(false): ?>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputName" class="control-label">Document Type</label>
                            <input type="text" name="document_type" class="form-control" placeholder="Ex. PKL">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputName" class="control-label">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="Ex. Packing List">
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-row">
                        <div class="form-group">
                            <select multiple="multiple" name="doc_type[]">
                            <?php foreach ($this->cw_document_type as $key => $value) {
                                // if(!empty($value->id)) {
                                    $selected = (in_array($value->doc_type, $selected_doc)) ? 'selected' : '';
                                    echo $selected;
                                    echo '<option value="'.$value->doc_type.'" '.$selected.' >['. $value->doc_type . '] ' . $value->description.'</option>';
                                // }
                            } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="vert-tabs-general" role="tabpanel" aria-labelledby="vert-tabs-general-tab">
                Coming soon...
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function () {
    $('select[name="doc_type[]"]').bootstrapDualListbox({
        nonSelectedListLabel: 'Hide',
        selectedListLabel: 'Show',
        preserveSelectionOnMove: 'moved',
        moveOnSelect: false,
        helperSelectNamePostfix: '_helper',
        sortByInputOrder: false,
        filterTextClear: "",
        selectorMinimalHeight: 300 
    });
    $('form#update_info').submit(function(event) {
        var loader = '<div id="spinner" class="d-flex justify-content-center">' +
                    '<div class="spinner-border" role="status">' + 
                    ' <span class="sr-only">Loading...</span>' + 
                    '</div>' +
                    '</div>';
       
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: document.location.origin + "/contact/update/<?= $this->contact_info->ucid; ?>/information",
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
    $('form#document_type').submit(function(event) {
        var loader = '<div id="spinner" class="d-flex justify-content-center">' +
                    '<div class="spinner-border" role="status">' + 
                    ' <span class="sr-only">Loading...</span>' + 
                    '</div>' +
                    '</div>';
       
        event.preventDefault();

        $.ajax({
            type: "POST",
            url: document.location.origin + "/contact/update/<?= $this->contact_info->uid; ?>/document",
            data: { 
                data: $('[name="doc_type[]"]').val(),
                account_id: <?=$this->contact_info->account_id; ?>,
            },
            dataType: "JSON",
            beforeSend: function () {
                $('form#document_type > div').hide();
                $('form#document_type').append(loader);
            },
            success: function (res) {
                console.log(res);
                if(!res) {
                    $('#spinner').remove();
                    $('form#document_type > div').remove();
                    $('form#document_type').html('Successfully updated');
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