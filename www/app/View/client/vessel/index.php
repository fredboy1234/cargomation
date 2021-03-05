
<style>
 .vessel-container{
     cursor: pointer;
 }
 .dcontent{
    display: contents;
    color: #343a40!important;
 }
 .group a,
 .group tr{
     color:#fff !important;
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
                    <span>Click each container to check details.</span>
                    <div class="row card-body table-responsive p-0 ttable" style="height: 500px;">
                    <table class="table table-hover table-head-fixed text-nowrap">
                    <thead>
                      <tr>
                            <th>Container Number</th>
                            <th>Vessel Name</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Voyage</th>
                      </tr>
                    </thead>
                  </table>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
</section>
</div>