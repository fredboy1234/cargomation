<style>
    #chartdiv{
        width: 100%;
        height: 670px;

    }

</style>
<?php
$transImage = "";
switch ($this->searatesTracking[0]->transport_mode) {
    case 'Sea':
        $transImage = "ship";
        break;
    case 'Air':
        $transImage = "plane";
        break;    
    default:
        $transImage = "truck";
        break;
} ?>
<?php $searates = $this->searatesTracking?>
<section class="content">
<a class="nav-link active" href="/vessel" data-toggle="tab">Show All Vessels</a>
<div class="container-fluid">
<div id="chartdiv" class="col-md-12"></div>
</div>
</div>

<script>
    var searates = <?php echo json_encode($searates)?>;
    var transImage = "<?= $transImage ?>"; 
</script>