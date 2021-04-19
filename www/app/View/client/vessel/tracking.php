<?php if(!empty($this->vesselyod) || $this->vesselyod !=""){?>
<script type="text/javascript">

    // Map appearance
    var width="100%";         // width in pixels or percentage
    var height="500px";         // height in pixels
    var names=true;           // always show ship names (defaults to false)
    var veselyod = <?=json_encode($this->vesselyod)?>;
    
    // Single ship tracking
    var imo = veselyod;        // display latest position (by IMO, overrides MMSI)
    var show_track=true;      // display track line (last 24 hours)
</script>
<script type="text/javascript" src="https://www.vesselfinder.com/aismap.js"></script>
<?php }else{?>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                <div class="card">
                    <h3 class="text-center">Can't Access Vessel Live Tracking Because of Empty VeselLyods.</h3>
                    <span class="text-center">(Please Contact your Admin for more details.)</span>
                </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php }?>