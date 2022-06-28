<?php
 $cwresponse = $this->data;
 if(!empty($cwresponse)){
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
    $searchWord = array('Successfully saved Consol','Updated Consol','Updated Shipment','Added Shipment');
    ?>

    <div class="col-12 col-sm-12 mt-3">
        <div class="card card-primary card-outline card-outline-tabs">
        <ul class="list-group list-group-flush">
            <?php foreach($arrText as $text){ ?>
                
                <?php 
                    foreach($searchWord  as $word){
                       
                        if(preg_match("/".$word."/", $text)){
                            echo '<li class="list-group-item">'.str_replace('"}', '', $text).'</li>';
                        }
                    }
                ?>
            <?php } ?>
        </ul>
        </div>
    </div>
<?php } ?>
