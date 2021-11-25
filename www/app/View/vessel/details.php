<?php $vesseldata = $this->vesseldata; ?>
<?php $bg=array('bg-danger','bg-success','bg-primary','bg-warning')?>
<?php $countBG=0; $count=0; $icon='fa-box';?>
<?php $c_flag = $this->c_flag;?>
<style>
    #custom-map{
        background: lightblue url("../img/vessel/worldmap.png") no-repeat fixed center;
        background-size: cover;
        padding-top: 5rem;
        padding-bottom: 5rem;
    }
    .a2b-map-wrapper{
        position: relative;
        z-index: 2;
        width: 100%;
        padding: 0 2.5rem;
        color: #001297;
    }
    @media screen and (min-width: 62.5rem){
        .a2b-map-wrapper{
            height: 8.125rem;
            padding: 0 8.125rem;}
    }
    .a2b-map-route{
        top: 50%;
        flex-flow: row;
        height: 0.25rem;
        margin: 0;
        border-top: 0.25rem dashed #001297;
        border-left: 0 none;
        position: inherit;
    }
    .o-trackingnomap--gauge{
        position: absolute;
        left: -0.25rem;
        background: #001297;
        top: -4px;
        height: 0.25rem !important;
    }
    #pol,#pad{ list-style: none;}
    #pol:after{
        content: '';
        position: absolute;
        top: 0;
        left: -0.125rem;
        height: 1.5rem;
        width: 1.5rem;
        margin-top: -0.75rem;
        margin-left: -0.75rem;
        border-radius: 50%;
        border: 0.125rem solid #001297;
        background-color: transparent;
    }
    #pol:before,
    #pad:before{
        content: '';
        position: absolute;
        top: 0;
        left: -0.125rem;
        height: 0.75rem;
        width: 0.75rem;
        margin-top: -0.375rem;
        margin-left: -0.375rem;
        border-radius: 50%;
        background-color: #001297;
    }
    .o-trackingnomap--marker{
        content: '';
        position: absolute;
        top: 100%;
        right: 0;
        display: block;
        border-radius: 50%;
    }
    .a2b-marker-icon{
        position: absolute;
        top: 0;
        right: 0;
        width: 1.25rem;
        height: 1.25rem;
        -webkit-transform: rotate(
    0deg
    ) translate(0%, -65%);
        transform: rotate(
    0deg
    ) translate(0%, -65%);
        font-size: 1.4rem;
        color: #fb0000;
    }
    .a2b-marker-icon:before{
        height: 2.25rem;
        width: 2.25rem;
        margin-top: -1.125rem;
        margin-right: -1.125rem;
        background-color: rgba(255, 255, 255, 0.2);
        box-shadow: 0 0 1.25rem 0 rgb(0 0 0 / 50%);
        padding: 7px;
        border-radius: 24px;
    }
    .o-trackingnomap--transport{
        left: 0;
        top: auto;
        bottom: 1.875rem;
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
        position: absolute;
        top: 0;
        left: 2.1875rem;
        min-width: 11.875rem;
        padding: 1.25rem 0.9375rem;
        border-radius: 0.375rem;
        text-align: center;
        font-size: 0.875rem;
        color: #fff;
        background: rgba(0, 18, 151, 0.8);
        box-shadow: 0 0 5px rgb(0 0 0 / 80%);
        -webkit-transform: translate(-67%, -174%);
        transform: translate(-67%, -174%);
    }
    .a2b-dis1{
        position: absolute !important;
        left: -3.7rem;
        top: 2.20rem;
        white-space: nowrap;
        -webkit-transform: translate(-50%, 0);
        transform: translate(-50%, 0);
        font-weight: 700;
        font-size: 0.875rem;
        display: block;
        position: relative;
        padding: 0.625rem 1.25rem;
        text-transform: uppercase;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        background: rgba(247, 247, 247, 0.6);
        color: #001297;
        border-radius: 1.25rem;
        width: 20px;
    }
    .a2b-dis2{
        position: absolute !important;
        right: -3.7rem;
        top: 2.20rem;
        white-space: nowrap;
        -webkit-transform: translate(-50%, 0);
        transform: translate(-53%, 0);
        font-family: opensans__bold, Open Sans, sans-serif;
        font-weight: 700;
        font-size: 0.875rem;
        display: block;
        position: relative;
        padding: 0.625rem 1.25rem;
        text-transform: uppercase;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
        background: rgba(247, 247, 247, 0.6);
        color: #001297;
        border-radius: 1.25rem;
    }
    #mapid { height: 760px; }
    .myDivIcon{
        background: none;
        border: none;
        color: #fb0000;
        font-size: 1.4rem;
        background: #fffc;
        text-align: center;
        border-radius: 21px;
        box-shadow: 0 0 1.25rem 0 rgb(0 0 0 / 50%);
        padding: 3px;

    }
    #refreshButton {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px;
        z-index: 400;
    }
    .flag{
        width: 40px;
        height: 30px;
    }
    .head-list{
        background: rgb(38 56 75 / 48%);
        padding-top: 6px;
        padding-bottom: 6px;
        color: #fff;
    }
    #sidebar,
    .more-details .card{
        background: rgb(7,27,47);
        color: #fff;
    }
    .estmd{
        border-right: 1px solid #fff;
    }
    .actual,.estmd{
        font-size: 11px;
        font-weight: bold;
    }
    #showmore{
        cursor: pointer;
    }
    .leaflet-sidebar{
        z-index: 500 !important;
    }
</style>
<?php $count =1;
    $origin = '';
    $destination ='';
    $totalLength = 100;
    $timelineLength = count($vesseldata);
    //$aveLoading = $totalLength / $timelineLength;
    //$loadingPercentage = 0;
    $vesselName = '';
    $id = 0;
    $ppolact = '';
    $ppolnot='<i class="text-danger">Not Available</i>';
?>
<?php foreach($vesseldata as $vdata){
    $date_now = new DateTime();
    $timedate    = new DateTime($vdata->ata);
    
    if ($timedate < $date_now) {
       // $loadingPercentage +=$aveLoading; 
        $vesselName = $vdata->vessel_name;
    }

    if($count == 1){
        $origin = $vdata->port_loading; 
    }
    if($count == $timelineLength){
        $destination = $vdata->port_discharge;
    }
   
    $count++;
} ?>
<?php $searates = $this->searatesTracking;?>
<?php if(!empty($searates[0])){
    //$searates = json_decode($searates[0]->sea_json);
    //$searates = json_decode($searates); 
  
}?>
 
<section class="content">
        <div class="container-fluid">
        <a href="/vessel">Show List Of Vessel</a>
            <div class="row">
                <div class="col-12">
                <div id="sidebar"></div>
                <div id="mapid" class="col-md-12"></div>
                <button id="refreshButton" type="button" class="btn btn-primary">Show Timeline</button>
                <div class="d-none tab-pane col-md-4 float-right" id="timeline">
                    <!-- The timeline -->
                    <h5 class="mb-2">Port Calls</h5>
                    <hr>
                    <div class="row">
                        <span class="col-md-5 d-inline-block text-center">
                            <h6>Estimated</h6>
                        </span>
                        <span class="col-md-5 d-inline-block text-center">
                            <h6>Actual</h6>
                        </span>
                    </div>         
                    <div class="timeline timeline-inverse">
                        <?php if(!isset($searates->data)){ exit;?>
                            
                        <?php } ?>
                        <?php if(!empty($searates->data) && $searates->message =="OK"){?>
                            <?php foreach($searates->data->locations as $vessel){ ?>
                               
                                <div class="time-label">
                                    <div class="head-list timeline-header">
                                        <span class="col-md-8 d-inline-block">
                                            <strong><?=$vessel->name.', '.$vessel->country?></strong>
                                        </span>
                                    </div>
                                    <div class="timeline-body pl-5">
                                        <?php $prepol = $searates->data->route->prepol;?>
                                        <?php $pol = $searates->data->route->pol;?>
                                        <?php $pod = $searates->data->route->pod;?>
                                        <?php $postpod = $searates->data->route->postpod;?>

                                        <?php if($prepol->actual == true){
                                                $ppolact = '<i class="text-white"> '.date("d/m/Y", strtotime($prepol->date)).'</i>';
                                            }
                                            if($prepol->actual == false){
                                                $ppolnot = '<i class="text-white"> '.date("d/m/Y", strtotime($prepol->date)).'</i>';
                                            }
                                        ?>

                                            <div class="estmd col-md-5 d-inline-block">
                                                <span>Prepol: <?=$ppolnot?> </span><br>
                                                <span>Pol:<i class="text-danger">Not Available</i></span><br>
                                                <span>Pod: <i class="text-danger">Not Available</i></span><br>
                                                <span>Postpod: <i class="text-danger">Not Available</i></span>
                                            </div>
                                            <div class="actual col-md-5 d-inline-block">
                                                <span>Prepol:<?=$ppolact?> </span><br>
                                                <span>Pol:<i class="text-danger">Not Available</i></span><br>
                                                <span>Pod: <i class="text-danger">Not Available</i></span><br>
                                                <span>Postpod: <i class="text-danger">Not Available</i></span>
                                            </div>
                                            <span id="showmore" data-show="<?=str_replace(' ','',$vessel->country)?>" class="d-block mt-3 mb-2 showmore"> Show More  &#8595;</span>
                                            <div class="more-details d-none" id="<?=str_replace(' ','',$vessel->country)?>">
                                                <div class="card card-body">
                                                <table class="table-striped table-dark">
                                                    <thead>
                                                        <tr>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Description</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Actual</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach($searates->data->container->events as $det){?>
                                                        <?php if($det->location == $vessel->id){?>
                                                            <?php $dateTrack = date_create($det->date);?>
                                                            <?php $day = date_format($dateTrack,"D");?>
                                                            <?php $month = date_format($dateTrack,"M. j,y");?>
                                                            <?php $hour = date_format($dateTrack,'H:i:s')?>
                                                                    <tr>
                                                                        <th scope="row"><?=$month?></th>
                                                                        <td><?=$det->description?></td>
                                                                        <td><?=$det->status?></td>
                                                                        <td><?=$det->actual?></td>
                                                                    </tr>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                                </div>
                                            </div>
                                    </div>
                                </div>          
                                <!-- END timeline item -->
                            <?php } ?>
                        <?php }else{?> 
                            
                        <?php } ?> 
                         
                    </div>
                    <hr>
                        <h6>Legend:</h6><br>
                        <span>Prepol: <strong> Port of Dispatch</strong></span> | <span>Pol: <strong> Port of Loading</strong></span><br>
                        <span>Pod: <strong> Port of Port Of Discharge</strong></span> | <span>Postpod: <strong> Destination/Final</strong></span> 
                    </div>
                </div>
            </div>
        </div>
</section>
</div>
<?php 
    $routemap = '{
        "status": "success",
        "message": "OK",
        "data": {
            "route" : [
                {
                    "path": [...], // 2247 items,
                    "type": "LAND"
                },
                {
                    "path": [
                        [
                            39.51394,
                            -121.50776
                        ],
                        [
                            37.55176504165017,
                            -122.54747555223851
                        ],
                        [
                            37.8227,
                            -121.27661
                        ]
                    ],
                    "type": "SEA",
                }
            ],
            "pin": [
                40.83242,
                -115.76312
            ]
        }
    }';
?>

<?php $rr = json_decode($routemap);?>
<script>
    var mapToken = <?php echo json_encode($this->mapToken);?>;
    var geocodeToken = <?php echo json_encode($this->geocodeToken)?>;
    var data = <?= json_encode($vesseldata)?>;
    //https://us1.locationiq.com/v1/search.php?key=pk.fe49a0fae5b7f62ed12a17d8c2a77691&q=cebu&format=json
    var geoAPIURL = 'https://us1.locationiq.com/v1/search.php';
    var geoAPIcitySearch = '';
    var geoAPIFormat = 'json';
    var datapolyline = <?=json_encode($this->polyline)?>;
    var vnum = <?=json_encode($this->vesselnum)?>;
    var searates =<?=json_encode($searates)?>;
    //console.log($searates);
</script>