
<?php $vesseldata = $this->vesseldata; ?>
<?php $bg=array('bg-danger','bg-success','bg-primary','bg-warning')?>
<?php $countBG=0; $count=0; $icon='fa-box';?>
<section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                <div class="tab-pane" id="timeline">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                        <?php if(!empty($vesseldata)){?>
                            <?php foreach($vesseldata as $vessel){ ?>
                                <?php $dateTrack = date_create($vessel->date_track);?>
                                <?php $day = date_format($dateTrack,"l");?>
                                <?php $month = date_format($dateTrack,"F j,Y");?>
                                <?php $hour = date_format($dateTrack,'h:i:s A')?>
                                <div class="time-label">
                                    <span class="<?=$bg[$countBG]?>">
                                        <?=$month?>
                                    </span>
                                </div>
                                <div>
                                    <?php if($count == 0){
                                        $icon = 'fa-box';
                                    }elseif($count == 1){
                                        $icon = 'fa-check-circle';
                                    }elseif($count == 2){
                                        $icon = 'fa-truck-loading';
                                    }elseif($count==3){
                                        $icon ='fa-sign-out-alt';
                                    }elseif($count==4){
                                        $icon = 'fa-plane-departure';
                                    }elseif($count==5){
                                        $icon = 'fa-plane-arrival';
                                    }?>

                                    <i class="fas <?=$icon?> bg-primary"></i>

                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> <?=$hour?></span>

                                        <h3 class="timeline-header"><a href="#"><?=$vessel->moves?></a> (<?=$day?>)</h3>

                                        <div class="timeline-body">
                                            <ul>
                                                <li><strong> Number:</strong> <?=$vessel->container_number?></li>
                                                <li><strong>Location:</strong> <?=$vessel->location_city?></li>
                                                <li><strong>Vessel:</strong> <?=$vessel->vessel?></li>
                                                <li><strong>Voyage:</strong> <?=$vessel->voyage?></li>
                                            </ul>
                                        </div>
                                        <div class="timeline-footer">
                                        </div>
                                    </div>
                                </div>
                                <!-- END timeline item -->
                                <?php $count++;$countBG++; if($countBG>3){$countBG=0;}?>
                            <?php } ?>
                        <?php } ?>
                        <div>
                            <i class="far fa-clock bg-gray"></i>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
</section>
</div>