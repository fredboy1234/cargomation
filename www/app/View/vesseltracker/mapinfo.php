<!--customized css for vessel time line-->
<style>
   .cus_vessel_tline {
   width:800px;
   height: 20px;
   list-style: none;
   text-align: justify;
   margin: 80px auto;
   background: -webkit-gradient(left top, left bottom, color-stop(0%, rgba(255,255,255,0)), color-stop(45%, rgb(36, 20, 230)), color-stop(51%, rgba(191,128,11,1)), color-stop(57%, rgba(255,255,255,0)), color-stop(100%, rgba(255,255,255,0)));
   background: linear-gradient(to bottom, rgba(255,255,255,0) 0%, rgba(255,255,255,0) 45%, rgba(191,128,11,1) 51%, rgba(255,255,255,0) 57%, rgba(255,255,255,0) 100%);
   }
   .progress{
      height:10px;
   }
   .cus_vessel_tline:after {
   display: inline-block;
   content: "";
   width: 100%;
   }
   .cus_vessel_tline li {
   display: inline-block;
   width: 20px;
   height: 20px;
   background: #F2BB13;
   text-align: center;
   line-height: 1.2;
   position: relative;
   border-radius: 50%;
   }
   .cus_vessel_tline li:before {
   display: inline-block;
   content: attr(data-year);
   font-size: 12px;
   position: absolute;
   font-weight: bold;
   left: 50%;
   transform: translateX(-50%);
   }
   .cus_vessel_tline li:nth-child(odd):before {
   top: -40px;
   }
   .cus_vessel_tline li:nth-child(even):before {
   bottom: -40px;
   }
   .cus_vessel_tline li:after {
   display: inline-block;
   content: attr(data-text);
   font-size: 10pt;
   position: absolute;
   left: 50%;
   transform: translateX(-50%);
   }
   .cus_vessel_tline li:nth-child(odd):after {
   bottom: 0;
   margin-bottom: -10px;
   transform: translate(-50%, 100%);
   }
   .cus_vessel_tline li:nth-child(even):after {
   top: 0;
   margin-top: -10px;
   transform: translate(-50%, -100%);
   }
</style>
<div class="col-lg-12">
   <div class="card card-primary card-outline card-tabs">
      <div class="card-header p-0 pt-1 border-bottom-0">
         <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
            <li class="nav-item">
               <a class="nav-link active" id="custom-tabs-three-home-tab" data-toggle="pill" href="#custom-tabs-three-home" role="tab" aria-controls="custom-tabs-three-home" aria-selected="true">Current Location</a>
            </li>
            <li class="nav-item">
               <a class="nav-link" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="false">Route Plan</a>
            </li>
         </ul>
      </div>
      <div class="card-body">
         <div class="tab-content" id="custom-tabs-three-tabContent">
            <div class="tab-pane fade active show" id="custom-tabs-three-home" role="tabpanel" aria-labelledby="custom-tabs-three-home-tab">
               <iframe name="vesselfinder" id="vesselfinder" src="https://www.vesselfinder.com/aismap?zoom=undefined&amp;lat=undefined&amp;lon=undefined&amp;width=100%&amp;height=500&amp;names=false&amp;imo=9813838&amp;track=false&amp;fleet=false&amp;fleet_name=false&amp;fleet_hide_old_positions=false&amp;clicktoact=false&amp;store_pos=false&amp;ra=livetracking_" width="100%" height="450" frameborder="0"></iframe>
            </div>
            <div class="tab-pane fade" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
               <ul class="cus_vessel_tline">
                  <li style="background: #57B96B;" data-year="ORIGIN" data-text="Singapore(SGSIN)"></li>
                  <li style="background: #57B96B;"data-year="POL" data-text="Singapore (SGSIN) 8 days in POL"></li>
                  <li style="background: #57B96B;"data-year="DIRECT JOURNEY" data-text=""></li>
                  <li style="background: #57B96B;"data-year="POD" data-text="Ho Chi Minh (VNSGN)"></li>
                  <li style="background: #F22F2F;"data-year="DESTINATION" data-text="Ho Chi Minh (VNSGN)"></li>
               </ul>
               <iframe name="vesselfinder" id="vesselfinder" src="https://route.vesselfinder.com/" width="100%" height="450" frameborder="0"></iframe>
            </div>
         </div>
      </div>
   </div>
</div>

