<?php
 $cwresponse = $this->data;
 $arrText = preg_split('/\r\n|\r|\n/', $cwresponse);
 $logs =array();
$spited = preg_split('/\.\s*?(?=[A-Z])|(\r\n|\n|\r)/', $cwresponse);
?>
<?= $this->getCSS(); ?>


<?php 
  foreach($arrText as $sp){
    if (strpos($sp, 'Error - ') !== false) {
       $logs['error'][] = $sp;
    }

    if (strpos($sp, 'Warning - ') !== false) {
        $logs['warning'][] = $sp;
     }
  }
?>
<?php if($this->cwstatus === "Failed"){?>
    <h5 class="text-danger text-center">Failed to Push to CW!
    </h5>
    <p class="text-small text-center text-muted">Please Check the following Error Logs Below.</p>
<?php } ?>
<?php if($this->cwstatus === "Success"){?>
    <h5 class="text-success text-center">Successfully to Push to CW!
    </h5>
<?php } ?>
<div class="col-12 col-sm-12 mt-3">
    <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link text-danger active" id="custom-tabs-four-error-tab" data-toggle="pill" href="#custom-tabs-four-error" role="tab" aria-controls="custom-tabs-four-error" aria-selected="false">Error Logs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-warning" id="custom-tabs-four-warning-tab" data-toggle="pill" href="#custom-tabs-four-warning" role="tab" aria-controls="custom-tabs-four-warning" aria-selected="false">Warning Logs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-success" id="custom-tabs-four-success-tab" data-toggle="pill" href="#custom-tabs-four-successs" role="tab" aria-controls="custom-tabs-four-successs" aria-selected="true">Success Logs</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="custom-tabs-four-tabContent">
                <div class="tab-pane fade active show" id="custom-tabs-four-error" role="tabpanel" aria-labelledby="custom-tabs-four-error-tab">
                    <?php if(!empty($logs['error'])){ ?>
                        <?php foreach($logs['error'] as $err){ ?>
                            <?php $error = str_replace("Error - ", " - ", $err);?>
                        <ul class="list-group">
                            <li class="list-group-item"><?=$error?></li>
                        </ul>
                        <?php } ?>
                    <?php }?>
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-warning" role="tabpanel" aria-labelledby="custom-tabs-four-warning-tab">
                    <?php if(!empty($logs['warning'])){ ?>
                        <?php foreach($logs['warning'] as $err){ ?>
                            <?php $warning = str_replace("Warning - ", " - ", $err);?>
                        <ul class="list-group">
                            <li class="list-group-item"><?=$warning?></li>
                        </ul>
                        <?php } ?>
                    <?php }?>
                </div>
                <div class="tab-pane fade" id="custom-tabs-four-success" role="tabpanel" aria-labelledby="custom-tabs-four-success-tab">
                </div>
            </div>
        </div>
    </div>
</div>

