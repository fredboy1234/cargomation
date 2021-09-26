<?php
namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * Index Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class Vessel extends Core\Controller {

    public function __construct($requestMethod = '', $key = '', $value = '', $param = []) {
        // Create a new instance of the model shipment class.
        $this->Vessel = Model\Vessel::getInstance();
        // Create a new instance of the model document class.
        $this->Document = Model\Document::getInstance();
        // Create a new instance of the core view class.
        $this->View = new Core\View;

        $this->requestMethod = $requestMethod;
        $this->param = $param;
        $this->value = $value;
        $this->key = $key;
    }

    /**
     * Index: Renders the index view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function index($user = "") {

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // If no user ID has been passed, and a user session exists, display
        // the authenticated users profile.
        if (!$user) {
            $userSession = Utility\Config::get("SESSION_USER");
            if (Utility\Session::exists($userSession)) {
                $user = Utility\Session::get($userSession);
            }
        }

        // // Get an instance of the user model using the user ID passed to the
        // // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        $role = $Role->getUserRole($user);

      
        if($role == 'user'){
            $shipment_id = $this->Shipment->getClientUserShipment($user, "shipment_num");
        }
       
        $role = $Role->getUserRole($user);

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        
        $selectedTheme = $User->getUserSettings($user);
        if(isset($selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = '';
        }
        
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/vessel.css");
        $this->View->addJS("js/vessel.js");
        
        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }
        $vessel = $this->Vessel->getSearatesDB($user);
        $confirmed = 0;
        $departCofirmed = 0;
        $delay = 0;
        $pending = 0;
        $doublechecker = array();
        $today = strtotime(date("Y-m-d"));
        $seadata = array();
        if(!empty($vessel)){
            foreach($vessel as $key=>$ves){
                $sea_json = json_decode($ves->sea_json);
                
                if(isset($sea_json->status ) && $sea_json->status === 'success'){
                    //print_r($sea_json);
                    $seadata[] = $sea_json;
                    if(isset($sea_json->data->container)){
                        $datacontainer = $sea_json->data->container;
                        $firstDate = isset($datacontainer->events[0]) ? $datacontainer->events[0]->date : '';
                        $lastDate = isset($datacontainer->events[0]) ? end($datacontainer->events)->date : '';
                        $statusEnd = isset($datacontainer->events[0]) ? end($datacontainer->events)->status : '';
                        // $today = strtotime(date("Y-m-d"));
                         $dateformat = strtotime($lastDate);

                         if(strtotime($lastDate) > strtotime('-30 days')){ 
                            if( $dateformat < $today){
                                $confirmed++; 
                            }else{
                        // print_r($statusEnd);
                            
                            // foreach($datacontainer->events as $cont){
                            //     $dateformat = strtotime($cont->date);
                            //     print_r($cont->status);
                            //     // if($today-$dateformat <= -86400 && $today-$dateformat >= -172800 ){
                            //     //     print_r($cont->date);
                            //     //     echo"<br>";
                            //     // }
                            //     echo"<br>";
                                
                            // }
                            
                            if($today-$dateformat <= -86400 && $today-$dateformat>= -172800 ){
                                $pending++;
                            }else if($today-$dateformat == 0){
                                $departCofirmed++;
                            }else{
                                $delay++;
                            }
                        }
                    }

                    }
                }else{
                    $seadata[] = $sea_json ;
                }

                $doublechecker[] = $ves;
            }

        }
      //exit;
        $this->View->renderTemplate("/vessel/index", [
            "title" => "Vessel Track",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "vessel" => $this->Vessel->getVessel($user),
            "image_profile" => $profileImage,
            'selected_theme' => $selectedTheme,
            'role' => $role,
            'mapToken' => 'pk.eyJ1IjoidGl5bzE0IiwiYSI6ImNrbTA1YzdrZTFmdGIyd3J6OXFhbHcyYTEifQ.R2vfZbgOCPtFG6lgAMWj7A',
            "notifications" => Model\User::getUserNotifications($user),
            "menu" => Model\User::getUserMenu($role->role_id),
            "sea_rates" => $seadata,
            "confirmed" => $confirmed,
            "pending" => $pending,
            "departCOnfirmed" => $departCofirmed,
            "delay" => $delay,
            'doublechecker'=>$this->Vessel->getOnestop(),
        ]);

        $this->externalTemp();

    }

    public function details($user=""){

         // Check that the user is authenticated.
         Utility\Auth::checkAuthenticated();

         // If no user ID has been passed, and a user session exists, display
         // the authenticated users profile.
         if (!$user) {
             $userSession = Utility\Config::get("SESSION_USER");
             if (Utility\Session::exists($userSession)) {
                 $user = Utility\Session::get($userSession);
             }
         }
 
         // // Get an instance of the user model using the user ID passed to the
         // // controll action. 
         if (!$User = Model\User::getInstance($user)) {
             Utility\Redirect::to(APP_URL);
         }
         if (!$Role = Model\Role::getInstance($user)) {
             Utility\Redirect::to(APP_URL);
         }
         $role = $Role->getUserRole($user);

         $uri = explode( '/', $_SERVER['REQUEST_URI'] );
         $vessel_number = '';
         if(isset($uri[2])){
            $vessel_number = explode('?',$uri[2]);
         }
        
         $selectedTheme = $User->getUserSettings($user);
        if(isset($selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = '';
        }
        
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/vessel.css");
        $this->View->addJS("js/vessel.js");
        
        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }

        $vessel_number = (isset($vessel_number[1])? $vessel_number[1] :'');
        $color_code_vessel = array();
        $t=0;
        $c_flag=array();
        $vesseeldata = $this->Vessel->getVesselByNumber($vessel_number,$user);

        foreach($vesseeldata as $ot){
            // if($t==0){
            //     $ot->date_track = '2021-05-28 11:31:00';
            // }
            if(strtotime($ot->date_track) < strtotime(date("Y-m-d h:i:s"))){
                $color_code_vessel['before'][] =  $ot;
            }else{
                $color_code_vessel['after'][] = $ot;
            } 
           
            $port_array = $this->Vessel->getSeaPort($ot->location_city);
            if(!empty($port_array)){
                $country = explode(",",$port_array[0]->port_name);
                $country_flag = $this->getFlag(trim(end($country)));
                $c_index = preg_replace('/\s*/', '', $ot->location_city);
                $c_flag[strtolower($c_index)][]=json_decode($country_flag)[0]->flag;
                
            }
           
            $t++;        
        }
        $searates  = 'empty';
    //    if(!isset($_SESSION['searates']) && empty($_SESSION['searates'])){    
    //        $searates = ''; //file_get_contents('https://tracking.searates.com/container?number='.$vessel_number.'&sealine=ANNU&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
    //        $tracking = '';//file_get_contents('https://tracking.searates.com/route?type=CT&number='.$vessel_number.'&sealine=ANNU&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
    //        $_SESSION['searates'] =  $searates;
    //        $_SESSION['tracking'] = $tracking;
    //     }
            $_SESSION['searates']=  $this->searatesRoute($vessel_number);
            $cont= file_get_contents('https://tracking.searates.com/container?number='.$vessel_number.'&sealine=auto&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
            // if(isset($_SESSION['searates'])){
            //     if($_SESSION['searates']->message ==='OK'){
            //         if(!empty($_SESSION['searates']->data)){
            //             $cont= file_get_contents('https://tracking.searates.com/container?number='.$vessel_number.'&sealine=auto&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
            //         }
            //     }
            
            // }
         
        $this->View->addJS("js/vessel.js");
        $this->View->renderTemplate("/vessel/details", [
            "title" => "Vessel Track",
            "vesseldata" => $vesseeldata,
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "image_profile" => $profileImage,
            'selected_theme' => $selectedTheme,
            'role' => $role,
            'mapToken' => 'pk.eyJ1IjoidGl5bzE0IiwiYSI6ImNrbTA1YzdrZTFmdGIyd3J6OXFhbHcyYTEifQ.R2vfZbgOCPtFG6lgAMWj7A',
            'geocodeToken' => 'pk.fe49a0fae5b7f62ed12a17d8c2a77691',
            "notifications" => Model\User::getUserNotifications($user),
            "polyline" => $color_code_vessel,
            "c_flag" => $c_flag,
            "vesselnum" => $vessel_number,
            "searatesTracking" =>$cont , //$this->Vessel->getSearatesByID($vessel_number),
            "tracking" => isset($_SESSION['tracking']) ? $_SESSION['tracking'] : '',
            "menu" => Model\User::getUserMenu($role->role_id)
        ]);
        $this->externalTemp();
    }

    public function tracking($user=""){

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // If no user ID has been passed, and a user session exists, display
        // the authenticated users profile.
        if (!$user) {
            $userSession = Utility\Config::get("SESSION_USER");
            if (Utility\Session::exists($userSession)) {
                $user = Utility\Session::get($userSession);
            }
        }

        // // Get an instance of the user model using the user ID passed to the
        // // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        $role = $Role->getUserRole($user);

        $uri = explode( '/', $_SERVER['REQUEST_URI'] );
        $vessel_number = '';
        if(isset($uri[2])){
           $vessel_number = explode('?',$uri[2]);
        }
       
        $selectedTheme = $User->getUserSettings($user);
       if(isset($selectedTheme[0]) && !empty($selectedTheme)){
           $selectedTheme = $selectedTheme[0]->theme;
       }else{
           $selectedTheme = '';
       }
       
       $this->View->addCSS("css/theme/".$selectedTheme.".css");
       $this->View->addCSS("css/vessel.css");
       
       
       $imageList = (Object) Model\User::getProfile($user);
       $profileImage = '/img/default-profile.png';
       foreach($imageList->user_image as $img){
           if( $img->image_src!="" && $img->image_type=='profile' ){
               $profileImage = base64_decode($img->image_src);
           }
       }

       $vessel_number = (isset($vessel_number[1])? $vessel_number[1] :'');
       $vloyds =(isset($this->Vessel->vesseLyod($vessel_number,$user)[0])? $this->Vessel->vesseLyod($vessel_number,$user)[0]->vesslloyds:'');
       
       $searates  = 'empty';
       if(!isset($_SESSION['vesselnum'])){
        $_SESSION['vesselnum'] = '';
       }
        //if(!isset($_SESSION['livesearates'])  ||  $_SESSION['vesselnum'] != $vessel_number ){
            //$searates = file_get_contents('https://tracking.searates.com/route?type=CT&number='.$vessel_number.'&sealine=CMDU&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
            //$searates = file_get_contents('https://tracking.searates.com/route?type=CT&number='.$vessel_number.'&sealine=CMDU&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
            //$_SESSION['livesearates'] =  $searates;
            //$_SESSION['vesselnum'] =$vessel_number;
        //}
        // print_r($this->Vessel->getSearatesByID($vessel_number));
        // exit();
        //echo"<pre>";
        $cont = '';
        $_SESSION['vessel_num']=  $this->searatesRoute($vessel_number);
         if(isset( $_SESSION['vessel_num'])){
             if($_SESSION['vessel_num']->message ==='OK'){
                 if(!empty($_SESSION['vessel_num']->data)){
                     $metadata = $_SESSION['vessel_num']->data;
                     $type = $metadata->metadata->type;
                     $sealine = $metadata->metadata->sealine;
                     $cont = file_get_contents('https://tracking.searates.com/route?type='.$type.'&number='.$vessel_number.'&sealine='.$sealine.'&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
                 }
             }
            
         }
        //exit();
        $this->View->addJS("js/tracking.js");
       $this->View->renderTemplate("/vessel/tracking", [
           "title" => "Vessel Track",
           "vesseldata" => $this->Vessel->getVesselByNumber($vessel_number,$user),
           "data" => (new Presenter\Profile($User->data()))->present(),
           "user" => (Object) Model\User::getProfile($user),
           "image_profile" => $profileImage,
           'selected_theme' => $selectedTheme,
           'role' => $role,
           'mapToken' => 'pk.eyJ1IjoidGl5bzE0IiwiYSI6ImNrbTA1YzdrZTFmdGIyd3J6OXFhbHcyYTEifQ.R2vfZbgOCPtFG6lgAMWj7A',
           'geocodeToken' => 'pk.fe49a0fae5b7f62ed12a17d8c2a77691',
           "notifications" => Model\User::getUserNotifications($user),
           "vesselyod" => $vloyds,
           "searatesTracking" => $cont,
           "menu" => Model\User::getUserMenu($role->role_id),
           //"searatesTracking" => $this->Vessel->getSearatesByID($vessel_number),

       ]);
   }

    public function vesselSSR($user=""){
        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // If no user ID has been passed, and a user session exists, display
        // the authenticated users profile.
        if (!$user) {
            $userSession = Utility\Config::get("SESSION_USER");
            if (Utility\Session::exists($userSession)) {
                $user = Utility\Session::get($userSession);
            }
        }

        // // Get an instance of the user model using the user ID passed to the
        // // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }
        //$vessel = $this->Vessel->getVessel($user);
        
        $vessel = $this->Vessel->getSearatesDB($user);
        
        //  echo "<pre>";
        // print_r($vessel);
        // exit();

        $data =array();
        $color = array();
        $store = array();
        $count = 0;
        $confirmed = 0;
        $doublechecker = array();
        //$json_data = array();
        if(!empty($vessel)){
            foreach($vessel as $key=>$ves){
                $mismatchETA = '';
                $mismatchETD = $mismatchVo =  $mismatchVsl='';
                $tcolor = $tcolorETD = $vtcolor = $tcolorvsl= '';
                
                $j_ves = json_decode($ves->sea_json);
                $subdata =array(); 
                $sealine  ='Not Specified';
                
                $lastdatrackCW = date_create($ves->eta);
                $lastdayCW = date_format($lastdatrackCW,"l");
                $lastmonthCW = date_format($lastdatrackCW,"M j,Y");
                $lasthourCW = date_format($lastdatrackCW,'h:i:s A');

                $firstdatrackCW = date_create($ves->etd);
                $firstdayCW = date_format($firstdatrackCW,"l");
                $firstmonthCW = date_format($firstdatrackCW,"M j,Y");
                $firsthourCW = date_format($firstdatrackCW,'h:i:s A');
                if(isset($j_ves->status) && $j_ves->status == 'success'){
                    
                    if(isset($j_ves->data->sealine)){
                        $sealine = $j_ves->data->sealine;
                    }
                    if(isset($j_ves->data) && !empty($j_ves->data)){
                        $vdata = $j_ves->data;
                        $vstatus = '';
                        $containernumber = isset($vdata->container->number) ? $vdata->container->number : 'No Container Number';
                        $firstLocation = isset($vdata->locations[0]) ? $vdata->locations[0]->name : 'No Location';
                        $endLocation =  isset($vdata->locations[0]) ? end($vdata->locations)->name : 'No Location';
                        $firstvessel = isset($vdata->vessels[0]) ? $vdata->vessels[0]->name : 'No Vessel Name';
                        $lastvessel = isset($vdata->vessels[0]) ? end($vdata->vessels)->name : 'No Vessel Name';
                        $firstDate = isset($vdata->container->events[0]) ? $vdata->container->events[0]->date : '';
                        $lastDate = isset($vdata->container->events[0]) ? end($vdata->container->events)->date : '';
                        $firstvoyage = isset($vdata->container->events[0]) ? $vdata->container->events[0]->voyage : 'No Voyage Name';
                        $lastvoyage = isset($vdata->container->events[0]) ? end($vdata->container->events)->voyage : 'No Voyage Name';

                        if($ves->port_discharge !== $endLocation){
                            $lastDate = $ves->eta;
                            $firstDate  = $ves->etd;
                        }
                       
                        $firstdatrack = date_create($firstDate);
                        $firstday = date_format($firstdatrack,"l");
                        $firstmonth = date_format($firstdatrack,"M j,Y");
                        $firsthour = date_format($firstdatrack,'h:i:s A');

                        $lastdatrack = date_create($lastDate);
                        $lastday = date_format($lastdatrack,"l");
                        $lastmonth = date_format($lastdatrack,"M j,Y");
                        $lasthour = date_format($lastdatrack,'h:i:s A');

                        $today = strtotime(date("Y-m-d"));
                        $enddate = strtotime($lastDate);
                        $colorscheme = 'not-done';
                        $statscheme = ' delays ';
                        
                        if($ves->port_discharge != $endLocation){
                            $lastDate = $ves->eta;
                        }
                        if(strtotime($lastDate) != strtotime($ves->eta)){
                            $mismatchETA = '<span id="match-'.$ves->id.'" class="mismatch d-none"> S.Line:'.$lastmonth.'-'.$lasthour .'<br> Cargomation:'.$lastmonthCW.'-'.$lasthourCW.'</span>';
                            $tcolor = 'text-danger';
                        }

                        if(strtotime($firstDate) != strtotime($ves->etd)){
                            $firsthour = date_format($firstdatrack,'h:i:s A');
                            $mismatchETD = '<strong>ETD</strong><br> S.Line:'.$firstmonth.'-'.$firsthour .'<br> Cargomation:'.$firstmonthCW.'-'.$firsthour;
                            $tcolorETD = 'text-danger';
                        }

                        if($ves->vessel_name !== $firstvessel){
                            $tcolorvsl = 'text-danger';
                            $mismatchVsl = '<span id="matchvsl-'.$ves->id.'" class="mismatchvsl d-none">Cargowise:'.$ves->vessel_name.'<br>S.Line:'.$firstvessel.'<br></span>';
                        }

                        if($ves->Voyage != $ves->voyage_flight_num){
                            $vtcolor = 'text-danger'; 
                            $mismatchVo = '<span id="matchVo-'.$ves->id.'" class="mismatchVo d-none">1stop:'.$ves->Voyage.'<br>S.Line:'.$ves->voyage_flight_num.'<br></span>';;
                        }
                        
                        $mismatchETA = '<span id="match-'.$ves->id.'" class="mismatch d-none">'.$mismatchETD.'<br><strong>ETA</strong><br>S.Line:'.$lastmonth.'-'.$lasthour .'<br> Cargomation:'.$lastmonthCW.'-'.$lasthourCW.'</span>';
                        
                        if($enddate < $today){
                            $colorscheme = 'done';
                            $statscheme = ' confirmedvessels ';
                        }

                        if($today-$enddate <= -86400 && $today-$enddate >= -172800 ){
                            $colorscheme = 'almost';
                            $statscheme = ' pending ';
                        }else if($today-$enddate == 0){
                            $colorscheme = 'completed';
                            $statscheme = ' departure ';
                        }

                        
                        if($containernumber !== 'No Container Number'){
                            $subdata['container_number'] = '<p id="'.$ves->id.'" data-header="contnum" class="'.$colorscheme.'">'.$containernumber.' <span class="d-none">'.$statscheme.'</span></p>';
                            $subdata['shipment_num'] = $ves->shipment_num;
                            //$subdata['housebill'] = $ves->house_bill;
                            $subdata['masterbill'] = $ves->master_bill;
                            $subdata['voyage'] = '<span data-match="matchVo-'.$ves->id.'" class="'.$vtcolor.' mmatchVo">'.(!empty($lastvoyage) ?  $lastvoyage: $ves->voyage_flight_num).'</span>'.$mismatchVo;

                            $subdata['date_track'] = 'ETD: <span class="'.$tcolorETD.' ">'.$firstmonth.'-'.$firsthour.'</span><br>  
                                                    ETA: <span data-match="match-'.$ves->id.'" class="'.$tcolor.' mmatchhover">'.$lastmonth.'-'.$lasthour.'</span>'.$mismatchETA;
                            $subdata['vessel_name'] ='<span data-match="matchvsl-'.$ves->id.'" class="mmatchhovervsl '. $tcolorvsl.'">'.$firstvessel.'</span>'.$mismatchVsl;
                            
                            $subdata['location_city'] = 'Origin: '.$firstLocation.'<br> Destination: '.$endLocation;
                            
                            $subdata['onestop'] = '<span class="onestop" id="'.$containernumber.'">View</span>';
                            
                            $subdata['shipping_line']  = $sealine;

                            $subdata['action'] = '<a class="col-sm-3 dcontent '.$key.'" href="/vessel/details?'.$containernumber.'">Details</a>/
                            <a class="col-sm-3 dcontent '.$containernumber.'" href="/vessel/tracking?'.$containernumber.'">Tracking</a>';
                        
                            //$data[] = $subdata; 
                        }
                        
                        
                    }
                }else{
                    //print_r($j_ves);
                    //$vstatus = '';
                    $containernumber = isset($j_ves->containernumber) ? $j_ves->containernumber : 'No Container Number';
                    $firstLocation = isset($j_ves->port_loading) ? $j_ves->port_loading : 'No Location';
                    $endLocation =  isset($j_ves->port_discharge) ? $j_ves->port_discharge : 'No Location';
                    //$firstvessel = isset($vdata->vessels[0]) ? $vdata->vessels[0]->name : 'No Vessel Name';
                    $lastvessel = isset($j_ves->vessel_name) ? $j_ves->vessel_name : 'No Vessel Name';
                    $firstDate = isset($j_ves->etd) ? $j_ves->etd : '';
                    $lastDate = isset($j_ves->eta) ? $j_ves->eta : '';
                    $firstvoyage = isset($j_ves->voyage_flight_num) ? $j_ves->voyage_flight_num : 'No Vessel Name';
                    //$lastvoyage = isset($vdata->container->events[0]) ? end($vdata->container->events)->voyage : 'No Vessel Name';

                    $firstdatrack = date_create($firstDate);
                    $firstday = date_format($firstdatrack,"l");
                    $firstmonth = date_format($firstdatrack,"M j,Y");
                    $firsthour = date_format($firstdatrack,'h:i:s A');

                    $lastdatrack = date_create($lastDate);
                    $lastday = date_format($lastdatrack,"l");
                    $lastmonth = date_format($lastdatrack,"M j,Y");
                    $lasthour = date_format($lastdatrack,'h:i:s A');

                    $today = strtotime(date("Y-m-d"));
                    $enddate = strtotime($lastDate);
                    $colorscheme = 'not-done';
                    $statscheme = ' delays ';

                    if($enddate < $today){
                        $colorscheme = 'done';
                        $statscheme = ' confirmedvessels ';
                    }

                    if($today-$enddate <= -86400 && $today-$enddate >= -172800 ){
                        $colorscheme = 'almost';
                        $statscheme = ' pending ';
                    }else if($today-$enddate == 0){
                        $colorscheme = 'completed';
                        $statscheme = ' departure ';
                    }

                    
                    if($ves->Voyage != $ves->voyage_flight_num){
                        $vtcolor = 'text-danger'; 
                        $mismatchVo = '<span id="matchVo-'.$ves->id.'" class="mismatchVo d-none">1stop:'.$ves->Voyage.'<br>S.Line:'.$ves->voyage_flight_num.'<br></span>';;
                    }

                    if($containernumber !== 'No Container Number'){
                        $subdata['container_number'] = '<p id="'.$ves->id.'" data-header="contnum" class="'.$colorscheme.'">'.$containernumber.' <span class="d-none">'.$statscheme.'</span></p>';
                        $subdata['shipment_num'] = $ves->shipment_num;
                        //$subdata['housebill'] = $ves->house_bill;
                        $subdata['masterbill'] = $ves->master_bill;
                        $subdata['voyage'] = '<span data-match="matchVo-'.$ves->id.'" class="'.$vtcolor.' mmatchVo">'.(!empty($lastvoyage) ?  $lastvoyage: $ves->voyage_flight_num).'</span>'.$mismatchVo;

                        $subdata['date_track'] = 'ETD: '.$firstmonth.'-'.$firsthour.'<br>  ETA: '.$lastmonth.'-'.$lasthour;
                        $subdata['vessel_name'] = $lastvessel;
                        
                        $subdata['location_city'] = 'Origin: '.$firstLocation.'<br> Destination: '.$endLocation;
                        
                        $subdata['onestop'] = '<span id="'.$containernumber.'">View</span>';
                        $subdata['shipping_line']  = $sealine;
                        $subdata['action'] = '<a class="col-sm-3 dcontent '.$key.'" href="/vessel/details?'.$containernumber.'">Details</a>/
                        <a class="col-sm-3 dcontent '.$containernumber.'" href="/vessel/tracking?'.$containernumber.'">Tracking</a>';
                    
                    }


                }
                if(!in_array($containernumber,$doublechecker) && $containernumber !== 'No Container Number'){
                    
                    if( strtotime($lastDate) > strtotime('-30 days') ){
                        $data[] = $subdata;
                        $doublechecker[]=$containernumber;
                    }
                    
                }
                 
            }
           
            $json_data=array(
                "data"  =>  $data,
            );
           
            
        }
       
        echo json_encode($json_data);
    }

    //temporary only please ignore
    public function externalTemp(){
        echo '<link rel="stylesheet" href="https://turbo87.github.io/leaflet-sidebar/src/L.Control.Sidebar.css" crossorigin=""/>';
        //  echo '<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        //  integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        //  crossorigin=""></script>';
        //echo '<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>';
         //echo '<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>';
        // echo '<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>';
        //echo '<script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>';
        //  echo '<script src="https://unpkg.com/leaflet/dist/leaflet-src.js"></script>';
        //  echo '<script src="https://unpkg.com/esri-leaflet"></script>';
        //echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/esri-leaflet-geocoder/3.0.0/esri-leaflet-geocoder.js"></script>';
        echo '<script src="https://turbo87.github.io/leaflet-sidebar/src/L.Control.Sidebar.js"></script>';
        echo ' <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.min.js"></script>';
        echo ' <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery.lazy/1.7.9/jquery.lazy.plugins.min.js"></script>';
    }

    public function scrape(){
        $data=array();
        if(isset($_POST)){
            
            $port = $this->Vessel->getSeaPort($_POST['port_name']);
            
            if(empty($port)){
                $dom = file_get_contents('https://us1.locationiq.com/v1/search.php?key=pk.fe49a0fae5b7f62ed12a17d8c2a77691&q='.$_POST['port_name'].'&format=json', false);
                $dom = json_decode($dom); 
                if(!empty($dom)){
                    foreach($dom as $prt){
                        $data['port_name'] = $prt->display_name;
                        $data['port_city'] = explode(',',$prt->display_name)[0];
                        $data['port_lat'] = $prt->lat;
                        $data['port_long'] = $prt->lon; 
                        $data['place_id'] = $prt->place_id;
                        $this->Vessel->addSeaPort($data);
                    }
                    
                }
            }
            
            echo json_encode($this->Vessel->getSeaPort($_POST['port_name']));
        }
    }
    
    // public function scrapeVessels(){
    //     $dom = file_get_contents('https://www.fleetmon.com/community/photos');
    //     echo "<pre>";
    //     print_r($dom);
    // }
    // public function country(){
    //     $data['name'] = $_POST['country_name']; 
    //     $data['code2'] = $_POST['country_code2']; 
    //     $data['code3'] = $_POST['country_code3']; 
    //     $data['area'] = $_POST['country_area']; 
    //     $data['region'] = $_POST['country_region']; 
    //     $data['flag'] = base64_encode(file_get_contents($_POST['country_flag'])); 
    //     $this->Vessel->addCountry($data);

    //     return json_encode($data);
    // }

    public function getFlag($country){
        return json_encode($this->Vessel->getFlag($country));
    }

    public function seaRatesToDB($user=""){
        Utility\Auth::checkAuthenticated();

        // If no user ID has been passed, and a user session exists, display
        // the authenticated users profile.
        if (!$user) {
            $userSession = Utility\Config::get("SESSION_USER");
            if (Utility\Session::exists($userSession)) {
                $user = Utility\Session::get($userSession);
            }
        }

        // // Get an instance of the user model using the user ID passed to the
        // // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $data['transhipment'] = $this->Vessel->getVesselV2($user);
        //echo "<pre>";
        // print_r($data['transhipment']);
        // exit;
        if(!empty($data['transhipment'])){
            
            foreach($data['transhipment'] as $trans){
                
                $data['trans_id'] = $trans[0]->id;
                $data['container_number'] = $trans[0]->containernumber;
                $data['json']  = '';
                $data['track'] = '';
                $data['user'] = $trans[0]->user_id;
                
                // if($data['track']  = file_get_contents('https://tracking.searates.com/route?type=CT&number='.$data["container_number"].'&sealine=ANNU&api_key=OEHZ-7YIN-1P9R-T8X4-F632')){
                //    echo'success';
                // } 
                try {

                    $_SESSION['vessel_num'] =  $this->searatesRoute($data["container_number"]);
                    if(isset( $_SESSION['vessel_num'])){
                        if($_SESSION['vessel_num']->message ==='OK'){
                            if(!empty($_SESSION['vessel_num']->data)){
                                $metadata = $_SESSION['vessel_num']->data;
                                $type = $metadata->metadata->type;
                                $sealine = $metadata->metadata->sealine;
                                $data['track'] = @file_get_contents('https://tracking.searates.com/route?type='.$type.'&number='.$data["container_number"].'&sealine='.$sealine.'&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
                            }
                        }  
                    }

                 
                    if($data['json']  = @file_get_contents('https://tracking.searates.com/container?number='.$data["container_number"].'&sealine=auto&api_key=OEHZ-7YIN-1P9R-T8X4-F632')){
                        //$this->Vessel->checkContainer($data);
                        $searates = json_decode( $data['json']);
                        //print_r($searates);
                        //print_r($trans[0]);
                        if($searates->status === "success" && $searates->message === 'OK'){
                            $eventsData = $searates->data->locations;
                            $eventsDate = end($searates->data->container->events)->date;
                            $lastDestination = isset($eventsData[0]) ? end($eventsData)->name :'';
                            //print_r( $lastDestination );
                            
                            if(trim($trans[0]->port_discharge) === trim($lastDestination) || strtotime($trans[0]->eta) >= strtotime($eventsDate)){
                                $this->Vessel->checkContainer($data);
                                echo $eventsDate;
                                echo '<br>'.$trans[0]->eta;
                            }else{
                                $data['json'] = json_encode($trans[0]);
                                $this->Vessel->checkContainer($data);
                            }
                            echo "<br> ";
                        }
                       
                    }
                    
                }
                catch(Exception $e) {
                    echo 'Message: ' . $e->getMessage();
                }
                    
             }
        }

        exit;
        //return  $this->Vessel->getVesselV2();
    }

    public function searatesIframe(){
        print_r($_GET);
    }
    public function searatesRoute($cnumber){
        //$cont = filge_get_contents('https://tracking.searates.com/route?type=CT&number=&sealine=ONEY&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
        $data = file_get_contents('http://tracking.searates.com/tracking?number='.$cnumber.'&sealine=auto&api_key=OEHZ-7YIN-1P9R-T8X4-F632');
        return json_decode($data);
    }
}