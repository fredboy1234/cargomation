<?php $livedata = $this->searatesTracking; //json_decode($this->searatesTracking);?>
<?php $livedata = json_decode($livedata[0]->track_json);?>
<style>
    #livemap { height: 760px; }
    .liveIcon{color:#fff;}
    .animated-icon{
        width: 30px;
        height: 30px;
        background-color: rgb(226 20 20 / 71%);
        border-radius: 50%;
        box-shadow: 0px 0px 4px #a03232;
        transition: all 1s 
    }
</style>
<section class="content">
    <div class="container-fluid">
    <a href="/vessel">Show List Of Vessel</a>
        <div class="row">
        
            <!-- <div class="tracking-block">
                <script type="text/javascript" src="https://www.searates.com/js/platform/container-tracking.js"></script>
                <div id="container-tracking-wrapper"></div>
                <script>
                    document.getElementById('container-tracking-wrapper').innerHTML =
                        '<iframe id="container-tracking" src="https://sirius.searates.com/tracking?' + window.location.href.split('?')[
                        1] +
                        '" width="100%" height="680px" frameborder="0" align="middle" scrolling="No" allowfullscreen="Yes"></iframe>';
                </script>
            </div> -->
            
         <?php if(isset($livedata->status) && $livedata->status !== 'success'){?>
            <h3 class="text-center">Live Tracking not Available.</h3>
            <span></span>
        <?php }else{ ?>
             <div id="livemap" class="col-md-12"></div> 
        <?php }?>
        </div>
        </div>
    </section> 
</div>
<script>
 var livedata = <?=json_encode($livedata)?>;
</script>