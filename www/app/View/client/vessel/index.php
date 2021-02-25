
<style>
 .vessel-container{
     cursor: pointer;
 }
 .dcontent{
    display: contents;
    color: #343a40!important;
 }
</style>
<?php $vessel = $this->vessel;?>
<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    <h3 class="card-title d-block">List of Containers</h3><br>
                    <div class="row">
                        <?php if(!empty($vessel)){?>
                            <?php foreach($vessel as $ves){?>
                                <a class="col-sm-3 dcontent" href="/vessel/details?<?=$ves[0]->container_number?>">
                                    <div class="col-sm-3 vessel-container">
                                        <div class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title"><?=$ves[0]->container_number?></h3>
                                        </div>
                                        <div class="card-body">
                                            <span>Vessel Name</span>
                                            <h4><?=$ves[0]->vessel?></h4>
                                            <br>
                                            <span>Voyage</span>
                                            <h4><?=$ves[0]->voyage?></h4>
                                        </div>
                                        </div>
                                    </div>
                                </a>
                            <?php }?>
                        <?php }?>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
</section>
</div>