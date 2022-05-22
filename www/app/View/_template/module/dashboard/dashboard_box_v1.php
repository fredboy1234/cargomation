 <!-- Small boxes (Stat box) -->
 <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <!-- <p class="bb-box">Total Shipments</p> -->
                <p class="bb-box">Sea FCL</p>
                <h3 class="text-dark total-shipment-FCL text-white">
                  <div class="spinner-border text-dark" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </h3>
              </div>
              <!-- <div class="icon">
                <i class="ion ion-cube text-dark"></i>
              </div> -->
              <div class="icon">
                <i class="ion ion-android-boat text-success"></i>
              </div>
              <a href="/shipment?search=container_mode&type=in&value=FCL" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <!-- <p class="bb-box">Sea Shipments</p> -->
                <p class="bb-box">Sea LCL</p>
                <h3 class="text-success Total-shipment-LCL text-white">
                  <div class="spinner-border text-success" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </h3>
              </div>
              <div class="icon">
                <i class="ion ion-android-boat text-success"></i>
              </div>
              <a href="/shipment?search=container_mode&type=in&value=LCL" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <p class="bb-box">Air Shipments</p>
                <h3 class="text-primary air-shipment">
                  <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </h3>
              </div>
              <div class="icon">
                <i class="ion ion-android-plane text-primary"></i>
              </div>
              <a href="/shipment?search=transport_mode&type=in&value=air" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-white">
              <div class="inner">
                <p class="bb-box">Order Pending Shipment</p>
                <h3 class="not-shipment" style="color:#fff !important;"><sup style="font-size: 20px"></sup>
                  <div class="spinner-border text-secondary" role="status">
                    <span class="sr-only">Loading...</span>
                  </div>
                </h3>
              </div>
              <div class="icon">
                <!-- <i id="custom_glass" class="fas fa-hourglass-end"></i> -->
                <i id="custom_glassv2"  class="fas fa-box"></i>
              </div>
              <!-- <a href="#" class="small-box-footer text-dark">More info <i class="fas fa-arrow-circle-right"></i></a> -->
              <a href="#" class="small-box-footer text-dark">Coming Soon</i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->