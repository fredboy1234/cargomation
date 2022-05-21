<?php
$table_header=array("Order Number","Ship/Dec No.","Order Date","Pre Advice","Buyer","Supplier","Transport Mode","Container Mode","Goods Origin","Goods Destination","Load Port","Dischargte Port","Packs","Type","Volume","UV","Weight","UW","Req. in Stock","Req. in Works","H.Bill","M.BIll");
?>
<style>
    #myTable1_length{
        display: none;
    }
    #myTable2_wrapper > div:nth-child(2),
    #myTable2_wrapper > div:nth-child(2){
        overflow: scroll;
    }
</style>
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row"><!--start of row class-->
    
           <!--start of column filter-->
            <div class="col-12 col-sm-12">
            <div id="fsearch" class="card card-default collapsed-card">
            <div class="card-header">
                <ul class="nav nav-pills float-left">
                    <li class="nav-item fsearch" data-card-widget="collapse"><a class="nav-link active" href="#vert-tabs" data-toggle="tab">Filter and Search</a></li>
                </ul>
                <ul class="nav nav-pills float-right">
                    <button type="button" id="newOrder" class="btn btn-outline-info"> New Order  <i style="font-size:12px" class="fa">&#xf067;</i></button>
                </ul>

                <div class="card-tools colp" style="line-height: 2.49em;">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="display: none;">
                <div class="tab-content2" id="">
                    <div class="tab-pane" id="vert-tabs">
                        <div class="row">
                            <div class="col-12 col-lg-2 col-md-2 col-sm-12">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-search-tab" data-toggle="pill" href="#vert-tabs-search" role="tab" aria-controls="vert-tabs-search" aria-selected="true">Advanced Search</a>
                                    <a class="nav-link" id="vert-tabs-settings-tab" data-toggle="pill" href="#vert-tabs-settings" role="tab" aria-controls="vert-tabs-settings" aria-selected="false">Column Filters</a>
                                    <a class="nav-link" id="vert-tabs-save-tab" data-toggle="pill" href="#vert-tabs-save" role="tab" aria-controls="vert-tabs-save" aria-selected="false">Save/Recent Search List</a>
                                </div>
                            </div>
                            <div class="col-12 col-lg-10 col-md-10 col-sm-12">
                                <div class="tab-content" id="vert-tabs-tabContent">
                                    <div class="tab-pane text-left fade active show" id="vert-tabs-search" role="tabpanel" aria-labelledby="vert-tabs-search-tab">
                                        <div class="active tab-pane" id="activity">
                                            <form id="addvance-search-form">
                                            <?php 
                                              include(VIEW_PATH."_template/module/searchfilter_order.php");
                                            ?>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-settings" role="tabpanel" aria-labelledby="vert-tabs-settings-tab">
                                        <div class="tab-pane" id="settings">
                                            <select multiple="multiple" size="10" name="settings-dual" title="settings-dual">
                                                <?php $settings = json_decode($this->user_settings); ?>
                                                <?php foreach ($settings as $value) { ?>
                                                    <?php $selected = ($value->index_check == "true" ? "selected='selected'" : ""); ?>
                                                    <?php $level = (isset($value->index_lvl) ? $value->index_lvl : "shipment") ?>
                                                    <option id="<?= $value->index_value ?>" 
                                                    class="settings-menu" 
                                                    value="<?= $value->index_value ?>" 
                                                    <?= $selected ?> 
                                                    data-index="<?= $value->index; ?>"
                                                    data-sort="<?= $value->index_sortable; ?>"
                                                    data-text="<?= $value->index_name ?>" 
                                                    lvl="<?= $level ?>">
                                                        <?= $value->index_name ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <div class="col-md-2 float-right parent-settings">
                                                <?php if (is_array($this->settings_user)) { ?>
                                                    <?php $settings_id = $this->settings_user[0]->id; ?>
                                                    <button id="reset-settings" type="button" data-setting-id="<?= $settings_id ?>" class="btn btn-block btn-danger">Set Default</button>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-save" role="tabpanel" aria-labelledby="vert-tabs-save-tab">
                                        <div class="tab-pane" id="save">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Save Search</label>
                                                        <select id="save_search" multiple="" class="form-control">
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Search Query</label>
                                                        <div id="query_text">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Recent Search</label>
                                                        <select id="recent_search" multiple="" class="form-control">
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mt-2">
                                                    <button type="button" id="resetSearch" class="btn btn-warning">
                                                        <i class="fas fa-undo"></i> Reset Selection
                                                    </button>
                                                    <button type="button" id="deleteSearch" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i> Delete Query
                                                    </button>
                                                </div>
                                                <div class="col-md-6 mt-2 text-right">
                                                    <button type="button" id="loadSearch" class="btn btn-success">
                                                        <i class="fas fa-search"></i> Load Search
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                    </div>
                                    <!--
                                    <div class="tab-pane fade" id="vert-tabs-profile" role="tabpanel" aria-labelledby="vert-tabs-profile-tab">
                                        Mauris tincidunt mi at erat gravida, eget tristique urna bibendum. Mauris pharetra purus ut ligula tempor, et vulputate metus facilisis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Maecenas sollicitudin, nisi a luctus interdum, nisl ligula placerat mi, quis posuere purus ligula eu lectus. Donec nunc tellus, elementum sit amet ultricies at, posuere nec nunc. Nunc euismod pellentesque diam. 
                                    </div>
                                    <div class="tab-pane fade" id="vert-tabs-messages" role="tabpanel" aria-labelledby="vert-tabs-messages-tab">
                                        Morbi turpis dolor, vulputate vitae felis non, tincidunt congue mauris. Phasellus volutpat augue id mi placerat mollis. Vivamus faucibus eu massa eget condimentum. Fusce nec hendrerit sem, ac tristique nulla. Integer vestibulum orci odio. Cras nec augue ipsum. Suspendisse ut velit condimentum, mattis urna a, malesuada nunc. Curabitur eleifend facilisis velit finibus tristique. Nam vulputate, eros non luctus efficitur, ipsum odio volutpat massa, sit amet sollicitudin est libero sed ipsum. Nulla lacinia, ex vitae gravida fermentum, lectus ipsum gravida arcu, id fermentum metus arcu vel metus. Curabitur eget sem eu risus tincidunt eleifend ac ornare magna. 
                                    </div>
                                    -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab2">

                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab3">

                    </div>
                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.card-body -->
            <div class="card-footer" style="display: none;">
            </div>
            <!-- /.card-footer -->
        </div>
    </div>
    <!--end of column filter-->

          <!--start of order table-->
          <div class="col-12 col-sm-12">
            <div class="card card-primary card-outline card-tabs">
              <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link filter-order all_fil" data-fil="all" id="custom-tabs-sea-tab" data-toggle="pill" href="#custom-tabs-sea" role="tab" aria-controls="custom-tabs-sea" aria-selected="false">
                            All
                            <span class="badge bg-success">0</span></a>
                    </li>
                 <?php if(!empty($this->filterButton)){?>
                   <?php foreach($this->filterButton as $filter){?>
                        <li class="nav-item">
                            <a class="nav-link filter-order <?=$filter->status?>_fil" data-fil="<?=$filter->status?>" id="custom-tabs-three-todispatch-tab" data-toggle="pill" href="#custom-tabs-three-todispatch" role="tab" aria-controls="custom-tabs-three-todispatch" aria-selected="false">
                                <?=$filter->status_desc?>
                                <span class="badge bg-success">0</span></a>
                        </li>
                    <?php } ?>
                 <?php } ?>
                  <!-- <li class="nav-item">
                    <a class="nav-link active" id="custom-tabs-sea-tab" data-toggle="pill" href="#custom-tabs-sea" role="tab" aria-controls="custom-tabs-sea" aria-selected="false">Incomplete <span class="badge bg-danger">113</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-todispatch-tab" data-toggle="pill" href="#custom-tabs-three-todispatch" role="tab" aria-controls="custom-tabs-three-todispatch" aria-selected="false">Confirmed <span class="badge bg-success">43</span></a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" id="custom-tabs-three-dispatch-tab" data-toggle="pill" href="#custom-tabs-three-dispatch" role="tab" aria-controls="custom-tabs-three-dispatch" aria-selected="false">Shipped <span class="badge bg-info">46</span></a>
                  </li> -->
                </ul>
              </div>
              <div class="card-body">
                <div class="tab-content" id="custom-tabs-three-tabContent">
                  <div class="tab-pane fade active show" id="custom-tabs-sea" role="tabpanel" aria-labelledby="custom-tabs-sea-tab">
                     <table id="myTable1" class="table table-striped table-bordered" width="100%" cellspacing="0">
	                    <thead>
	                        <tr>
	                        	<?php foreach ($table_header as $key => $value) { ?>
	                            <th><?php echo $value;?></th>
	                       	    <?php }?>
	                        </tr>
	                    </thead>
	                </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-three-todispatch" role="tabpanel" aria-labelledby="custom-tabs-three-todispatch-tab">
                    <table id="myTable2" class="table table-striped table-bordered" width="100%" cellspacing="0">
	                    <thead>
	                        <tr>
	                        	<?php foreach ($table_header as $key => $value) { ?>
	                            <th><?php echo $value;?></th>
	                       	    <?php }?>
	                        </tr>
	                    </thead>
	                </table>
                  </div>
                  <div class="tab-pane fade" id="custom-tabs-three-dispatch" role="tabpanel" aria-labelledby="custom-tabs-three-dispatch-tab">
                     <table id="myTable3" class="table table-striped table-bordered" width="100%" cellspacing="0">
	                    <thead>
	                        <tr>
	                        	<?php foreach ($table_header as $key => $value) { ?>
	                            <th><?php echo $value;?></th>
	                       	    <?php }?>
	                        </tr>
	                    </thead>
	                </table>
                  </div>
                </div>
              </div>
              <!-- /.card -->
            </div>
          </div>
          <!--end of order table-->

        </div><!--end of row class-->
    </div>
</section>
</div>
<script>  
var user_id = <?= $this->user_id; ?>;
</script>