<?php $livedata = json_decode($this->searatesTracking);?>
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
        <div id="livemap" class="col-md-12"></div>
        </div>
        </div>
    </section>
</div>
<script>
 var livedata = <?=json_encode($livedata)?>;

</script>