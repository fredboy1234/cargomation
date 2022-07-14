<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * Docdeveloper Controller:
 */

class Order extends Core\Controller {

    /**
     * Order Index: Renders the order view. NOTE: This controller can only be accessed
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

        // Get an instance of the user role
        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user);

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }


        if($role->role_id > 2) {
            $sub_account = $User->getSubAccountInfo($user);
            $user_key = $sub_account[0]->account_id;
        } else {
            $user_key = $user;
        }

        $selectedTheme = $User->getUserSettings($user);
        
        if(isset( $selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = 'default';
        }


        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/".$selectedTheme.".css");
        $this->View->addJS("js/order.js");

        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }
        $Order = Model\Order::getInstance();
        //$orderData = $Order->getOrderData($user);
        $filterButton = $Order->getFilterButton($user);
        
        $this->View->renderTemplate("/order/index", [
            "title" => "Order View",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "notifications" => Model\User::getUserNotifications($user),
            "menu" => Model\User::getUserMenu($user, $role->role_id),
            "image_profile" => $profileImage,
            'role' => $role, 
            'user_id' => $user,
            'selected_theme' => $selectedTheme,
            "user_settings" =>$User->defaultSettings($user_key, $role->role_id),
            "settings_user" => $User->getUserSettings($user),
            "filterButton"=>$filterButton,
        ]);
    }

   
    public function getCountsForFilterButton(){
        $user = $_SESSION['user'];
        $statsCount = array();
        $Order = Model\Order::getInstance();
        $orderData = $Order->getOrderData($user);
        $filterButton = $Order->getFilterButton($user);
        
        foreach( $filterButton as $val){
           $count = $Order->getOrderCountByStatus($user,$val->status);
           
           if(isset($count[0]) && isset($count[0]->cnt)){
            $statsCount[$val->status] = $count[0]->cnt;
           }else{
            $statsCount[$val->status] = 0;
           }
        }
      
        $statsCount['all'] = count($orderData);
       echo json_encode($statsCount);
    }

    public function getFilterResults(){
        $user = $_SESSION['user'];
        $retData = array();
        $Order = Model\Order::getInstance();
        $orderData = $Order->getFilterResults($user,$_POST['status']);

        foreach($orderData as $value){

            $obj = '<div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    View
                    </button>
                    <div class="dropdown-menu"><span class="dropdown-item">';

            $arr = json_decode($value->order_line);
            if(is_array($arr) && count($arr) > 0){
                foreach ($arr as $key => $object) {
                    $line_number = $obj."LineNumber: ".$object->LineNumber.'<br />'.
                    "Pkg Qty: ".$object->PackageQty.'<br />'.
                    "Order Qty: ".$object->OrderedQty.' '.$object->OrderedQtyUnitCode.'<br />'.
                    "Product: <b>".$object->ProductName.'</b><br />'.
                    '</span></div></div>';
                }
            }else{
                $line_number = $obj.'<span class="text-warning">No data</span></span></div></div>';
            }
           $var_mile = $var_mile_ocf = $var_mile_exw = $var_mile_gin = $var_mile_dep = $var_mile_arv = $var_mile_cav = $var_mile_dca = $var_mile_dcf = '';
           $arr1 = json_decode($value->milestone);

             foreach ($arr1 as $key => $object) {
                 if(strval($object->EventCode) === 'OCF'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_ocf = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'EXW'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_exw = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'GIN'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_gin = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'DEP'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_dep = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'ARV'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_arv = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'DCA'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_dca = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'DCF'){
                   if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_dcf = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
            }

            $var_mile = $var_mile_ocf.$var_mile_exw.$var_mile_gin.$var_mile_dep.$var_mile_arv.$var_mile_dca.$var_mile_dcf;

            $ord = '<div class="input-group">
                    <div class="input-group-prepend">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'.$value->order_number.'</button>
                    <div class="dropdown-menu" style="z-index:3">
                    <a href="javascript:void(0);" onclick="getData(\'' . $var_mile . '\')" class="dropdown-item milestone" data-toggle="modal" data-target="#modalCategory">View Milestones</a>
                    </div>
                    </div>';
            $retData['data'][] = array(
                "order_number" => '<b>'.$ord.'</b>',
                "order_date"=>$value->order_date,
                "status"=>$value->status_desc,
                "pre_advice"=>'',
                "buyer"=>$value->buyer,
                "supplier"=>$value->seller,
                "transport_mode"=>$value->trans_mode,
                "goods_origin"=>$value->goods_origin,
                "good_destination"=>$value->goods_destination,
                "load_port"=>$value->port_load,
                "discharge_port"=>$value->port_origin,
                "packs"=>$value->outer_pack,
                "type"=>$value->pack_code,
                "volume"=>$value->total_volume,
                "uv"=>'',
                "weight" =>$value->total_weight,
                "uw"=>$value->weight_unit,
                // "req_stock"=>'',
                // "req_work"=>'',  
                "order_line"=>$line_number
            );
        }
     
        echo json_encode($retData);
    }

    public function orderData(){
        $user_id = $_SESSION['user'];
        $retData = array();

        $Order = Model\Order::getInstance();
        $orderData = $Order->getOrderData($user_id);
        $orderline_arr = [];


        foreach($orderData as $value){

            $obj = '<div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    View
                    </button>
                    <div class="dropdown-menu"><span class="dropdown-item">';

            $arr = json_decode($value->order_line);
            if(is_array($arr) && count($arr) > 0){
                foreach ($arr as $key => $object) {
                    $line_number = $obj."LineNumber: ".$object->LineNumber.'<br />'.
                    "Pkg Qty: ".$object->PackageQty.'<br />'.
                    "Order Qty: ".$object->OrderedQty.' '.$object->OrderedQtyUnitCode.'<br />'.
                    "Product: <b>".$object->ProductName.'</b><br />'.
                    '</span></div></div>';
                }
            }else{
                $line_number = $obj.'<span class="text-warning">No data</span></span></div></div>';
            }
           $var_mile = $var_mile_ocf = $var_mile_exw = $var_mile_gin = $var_mile_dep = $var_mile_arv = $var_mile_cav = $var_mile_dca = $var_mile_dcf = '';
           $arr1 = json_decode($value->milestone);

             foreach ($arr1 as $key => $object) {
                 if(strval($object->EventCode) === 'OCF'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_ocf = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'EXW'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_exw = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'GIN'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_gin = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'DEP'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_dep = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'ARV'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_arv = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'DCA'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_dca = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'DCF'){
                   if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_dcf = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
            }

            $var_mile = $var_mile_ocf.$var_mile_exw.$var_mile_gin.$var_mile_dep.$var_mile_arv.$var_mile_dca.$var_mile_dcf;

            $ord = '<div class="input-group">
                    <div class="input-group-prepend">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'.$value->order_number.'</button>
                    <div class="dropdown-menu" style="">
                    <a href="javascript:void(0);" onclick="getData(\'' . $var_mile . '\')" class="dropdown-item milestone" data-toggle="modal" data-target="#modalCategory">View Milestones</a>
                    </div>
                    </div>';
            $retData['data'][] = array(
                "order_number" => '<b>'.$ord.'</b>',
                "order_date"=>$value->order_date,
                "status"=>$value->status_desc,
                "pre_advice"=>'',
                "buyer"=>$value->buyer,
                "supplier"=>$value->seller,
                "transport_mode"=>$value->trans_mode,
                "goods_origin"=>$value->goods_origin,
                "good_destination"=>$value->goods_destination,
                "load_port"=>$value->port_load,
                "discharge_port"=>$value->port_origin,
                "packs"=>$value->outer_pack,
                "type"=>$value->pack_code,
                "volume"=>$value->total_volume,
                "uv"=>'',
                "weight" =>$value->total_weight,
                "uw"=>$value->weight_unit,
                // "req_stock"=>'',
                // "req_work"=>'',  
                "order_line"=>$line_number
            );
        }


        echo json_encode($retData);
    }

    public function getOrderByShipment(){
        $user_id = $_SESSION['user'];
        $retData = array();

        $Order = Model\Order::getInstance();
        $orderData = $Order->getOrderByShipment($user_id);
        
        foreach($orderData as $value){

            $obj = '<div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    View
                    </button>
                    <div class="dropdown-menu"><span class="dropdown-item">';

            $arr = json_decode($value->order_line);
            if(is_array($arr) && count($arr) > 0){
                foreach ($arr as $key => $object) {
                    $line_number = $obj."LineNumber: ".$object->LineNumber.'<br />'.
                    "Pkg Qty: ".$object->PackageQty.'<br />'.
                    "Order Qty: ".$object->OrderedQty.' '.$object->OrderedQtyUnitCode.'<br />'.
                    "Product: <b>".$object->ProductName.'</b><br />'.
                    '</span></div></div>';
                }
            }else{
                $line_number = $obj.'<span class="text-warning">No data</span></span></div></div>';
            }
           $var_mile = $var_mile_ocf = $var_mile_exw = $var_mile_gin = $var_mile_dep = $var_mile_arv = $var_mile_cav = $var_mile_dca = $var_mile_dcf = '';
           $arr1 = json_decode($value->milestone);

             foreach ($arr1 as $key => $object) {
                 if(strval($object->EventCode) === 'OCF'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_ocf = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'EXW'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_exw = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'GIN'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_gin = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'DEP'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_dep = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'ARV'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_arv = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'DCA'){
                    if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_dca = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
                elseif(strval($object->EventCode) === 'DCF'){
                   if(!empty(strval($object->ActualDate))){$color_val = 'background:#28a745';$ata = date('Y-m-d', strtotime($object->ActualDate));}else{$color_val="background:#6c757d";$ata ='No Date';}
                    if(!empty(strval($object->EstimatedDate))){$est = date('Y-m-d', strtotime($object->EstimatedDate));}else{$est ='No Date';}
                    $var_mile_dcf = "<li style=\'{$color_val}\' data-year=\'{$object->Description}\' data-text=\'Estimated: ~{$est}~Actual: ~{$ata}  \'><span class=\'fa-li\'><i class=\'nav-icon fas fa-check\'></i></span></li>~";
                }
            }

            $var_mile = $var_mile_ocf.$var_mile_exw.$var_mile_gin.$var_mile_dep.$var_mile_arv.$var_mile_dca.$var_mile_dcf;

            $ord = '<div class="input-group">
                    <div class="input-group-prepend">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">'.$value->order_number.'</button>
                    <div class="dropdown-menu" style="">
                    <a href="javascript:void(0);" onclick="getData(\'' . $var_mile . '\')" class="dropdown-item milestone" data-toggle="modal" data-target="#modalCategory">View Milestones</a>
                    </div>
                    </div>';
            $retData['data'][] = array(
                "order_number" => '<b>'.$ord.'</b>',
                "order_date"=>$value->order_date,
                "status"=>$value->status_desc,
                "pre_advice"=>'',
                "buyer"=>$value->buyer,
                "supplier"=>$value->seller,
                "transport_mode"=>$value->trans_mode,
                "goods_origin"=>$value->goods_origin,
                "good_destination"=>$value->goods_destination,
                "load_port"=>$value->port_load,
                "discharge_port"=>$value->port_origin,
                "packs"=>$value->outer_pack,
                "type"=>$value->pack_code,
                "volume"=>$value->total_volume,
                "uv"=>'',
                "weight" =>$value->total_weight,
                "uw"=>$value->weight_unit,
                // "req_stock"=>'',
                // "req_work"=>'',  
                "order_line"=>$line_number
            );
        }
        
        echo json_encode($retData);
    }
}
