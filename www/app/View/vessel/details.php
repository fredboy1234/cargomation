<style>
    #chartdiv{
        width: 100%;
        height: 670px;

    }
    .sidebar{
        z-index: 9999999;
    }
    body{margin-top:20px;}
.timeline {
    border-left: 3px solid #727cf5;
    border-bottom-right-radius: 4px;
    border-top-right-radius: 4px;
    background: rgba(114, 124, 245, 0.09);
    margin: 0 auto;
    letter-spacing: 0.2px;
    position: relative;
    line-height: 1.4em;
    font-size: 1.03em;
    padding: 50px;
    list-style: none;
    text-align: left;
    max-width: 40%;
}

.sidebar-map{
    z-index: 10;
}

@media (max-width: 767px) {
    .timeline {
        max-width: 98%;
        padding: 25px;
    }
}

.timeline h1 {
    font-weight: 300;
    font-size: 1.4em;
}

.timeline h2,
.timeline h3 {
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 10px;
}

.timeline .event {
    border-bottom: 1px dashed #e8ebf1;
    padding-bottom: 25px;
    margin-bottom: 25px;
    position: relative;
}

@media (max-width: 767px) {
    .timeline .event {
        padding-top: 30px;
    }
}

.timeline .event:last-of-type {
    padding-bottom: 0;
    margin-bottom: 0;
    border: none;
}

.timeline .event:before,
.timeline .event:after {
    position: absolute;
    display: block;
    top: 0;
}

.timeline .event:before {
    left: -207px;
    content: attr(data-date);
    text-align: right;
    font-weight: 100;
    font-size: 0.9em;
    min-width: 120px;
}

@media (max-width: 767px) {
    .timeline .event:before {
        left: 0px;
        text-align: left;
    }
}

.timeline .event:after {
    -webkit-box-shadow: 0 0 0 3px #727cf5;
    box-shadow: 0 0 0 3px #727cf5;
    left: -55.8px;
    background: #fff;
    border-radius: 50%;
    height: 9px;
    width: 9px;
    content: "";
    top: 5px;
}

@media (max-width: 767px) {
    .timeline .event:after {
        left: -31.8px;
    }
}

.rtl .timeline {
    border-left: 0;
    text-align: right;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
    border-bottom-left-radius: 4px;
    border-top-left-radius: 4px;
    border-right: 3px solid #727cf5;
}

.rtl .timeline .event::before {
    left: 0;
    right: -170px;
}

.rtl .timeline .event::after {
    left: 0;
    right: -55.8px;
}
.timeline::before{
   left: 20px;
}
.timeline{
    max-width: 91%;
}
#show-details{
    position: absolute;
    right: 3px;
    z-index: 10;
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
<?php $routeleg = json_decode($searates[0]->route_leg);?>
<section class="content">
<a class="nav-link active" href="/vessel" data-toggle="tab">Show All Vessels</a>
<div class="container-fluid">
<button id="show-details" class="btn btn-primary float-right">Show Details</button>
<div class="sidebar-map d-none position-absolute">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">Timeline</h6>
                        <br>
                            <?php foreach($routeleg as $leg){?>
                            <div id="content">
                                <ul class="timeline">
                                    <li class="event" data-date="">
                                        <h3><?php echo "Leg Order - ".$leg->LegOrder?></h3>
                                        <p class="mb-1"><?php echo "Vessel Name: ".$leg->VesselName?></p>
                                        <p class="mb-1"><?php echo "Origin: ". $leg->Origin?></p>
                                        <p class="mb-1"><?php echo "Destination: ". $leg->Destination?></p>
                                    </li>
                                </ul>
                            </div>  
                <?php } ?>
                        </div>
                    </div> 
            </div>
        </div>
    </div>
</div>
<div id="chartdiv" class="col-md-12"></div>
</div>
</div>

<script>
    var searates = <?php echo json_encode($searates)?>;
    var transImage = "<?= $transImage ?>"; 
    console.log(searates);
</script>