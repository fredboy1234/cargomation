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
</style>
<?php $count =1;
    $origin = '';
    $destination ='';
    $totalLength = 100;
    $timelineLength = count($vesseldata);
    $aveLoading = $totalLength / $timelineLength;
    $loadingPercentage = 0;
    $vesselName = '';
    $id = 0;
?>
<?php foreach($vesseldata as $vdata){
    $date_now = new DateTime();
    $timedate    = new DateTime($vdata->date_track);
    
    if ($timedate < $date_now) {
        $loadingPercentage +=$aveLoading; 
        $vesselName = $vdata->vessel;
    }

    if($count == 1){
        $origin = $vdata->location_city;
    }
    if($count == $timelineLength){
        $destination = $vdata->location_city;
    }
   
    $count++;
} ?>
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
                    <div class="timeline timeline-inverse">
                        <?php if(!empty($vesseldata)){?>
                            <?php foreach($vesseldata as $vessel){ ?>
                                <?php $dateTrack = date_create($vessel->date_track);?>
                                <?php $day = date_format($dateTrack,"l");?>
                                <?php $month = date_format($dateTrack,"F j,Y");?>
                                <?php $hour = date_format($dateTrack,'h:i:s A')?>
                                <?php  $c_index = preg_replace('/\s*/', '', $vessel->location_city);?>
                                <?php $urlimage = (isset($c_flag[strtolower($c_index)][0]) ? $c_flag[strtolower($c_index)][0] : '')?>
                                
                                <div class="time-label" data-time="time-<?=$id?>">
                                    <div class="head-list">
                                    <span class="col-md-2 d-inline-block">
                                     <img class="flag f-<?=$vessel->location_city?> img-thumbnail" src="data:image/svg+xml;base64, <?=$urlimage?>">
                                    </span>
                                    <span class="col-md-8 d-inline-block">
                                         <strong><?=$vessel->location_city?></strong><br>
                                         <?=$month?>
                                    </span>
                                    </div>
                                </div>
                                
                                <!-- END timeline item -->
                                <?php $id++; $count++;$countBG++; if($countBG>3){$countBG=0;}?>
                            <?php } ?>
                        <?php } ?>
                    </div>
                    </div>
                </div>
            </div>
        </div>
</section>
</div>

<script>
    var mapToken = <?php echo json_encode($this->mapToken);?>;
    var geocodeToken = <?php echo json_encode($this->geocodeToken)?>;
    var data = <?= json_encode($vesseldata)?>;
    //https://us1.locationiq.com/v1/search.php?key=pk.fe49a0fae5b7f62ed12a17d8c2a77691&q=cebu&format=json
    var geoAPIURL = 'https://us1.locationiq.com/v1/search.php';
    var geoAPIcitySearch = '';
    var geoAPIFormat = 'json';
    var datapolyline = <?=json_encode($this->polyline)?>;
    console.log(data);
</script>
