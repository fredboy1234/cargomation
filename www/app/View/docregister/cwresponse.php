<?php
 $cwresponse = $this->data;
 if(!empty($cwresponse)){
    $arrText = preg_split('/\r\n|\r|\n/', $cwresponse);
    $logs =array();
    $spited = preg_split('/\.\s*?(?=[A-Z])|(\r\n|\n|\r)/', $cwresponse);
?>
    <?= $this->getCSS(); ?>
    <pre>
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

    <div class="col-12 col-sm-12 mt-3">
        <div class="card card-primary card-outline card-outline-tabs">
            <?php foreach($arrText as $text){ ?>
                <?php echo $text; //print_r(preg_match('/(Added Shipment)/',  $text, $matches, PREG_OFFSET_CAPTURE));?>
            <?php } ?>
        </div>
    </div>
<?php } ?>
