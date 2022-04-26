<?php
 $cwresponse = $this->data;
 $arrText = preg_split('/\r\n|\r|\n/', $cwresponse);
?>
<?= $this->getCSS(); ?>
<ul class="list-group list-group-flush">
    <?php foreach($arrText as $text){ 
        $stattext = ''; 
        if (strpos($text, 'Warning') !== false) {
            $stattext = '<span class="badge badge-warning">Warning</span> '; 
        }elseif(strpos($text, 'Successfully') !== false){
            $stattext = '<span class="badge badge-success">Success</span> '; 
        }else{
            $stattext =  '<span class="badge badge-primary">Process</span> ';
        }
        
        $ctext = str_replace('ProcessingLog'," ",$text);
        $ctext = str_replace('{'," ", $ctext);
        $ctext = str_replace('}'," ", $ctext);
        $ctext = str_replace(':'," ", $ctext);
    ?>
    
    <li class="list-group-item"><?=$stattext?> <?php echo str_replace('"'," ",$ctext);?></li>
    <?php } ?>
 
</ul>



