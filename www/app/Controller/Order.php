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
            $retData['data'][] = array(
                "order_number" => $value->order_number,
                "ship_num" =>'',
                "order_date"=>date_format(date_create($value->eta),"d/m/Y"),
                "pre_advice"=>'',
                "buyer"=>$value->buyer,
                "supplier"=>'',
                "transport_mode"=>$value->trans_mode,
                "container_mode"=>'',
                "goods_origin"=>$value->port_origin,
                "good_destination"=>'',
                "load_port"=>$value->port_load,
                "discharge_port"=>'',
                "packs"=>'',
                "type"=>'',
                "volume"=>$value->total_volume,
                "uv"=>'',
                "weight" =>$value->total_weight,
                "uw"=>$value->weight_unit,
                "req_stock"=>'',
                "req_work"=>'',
                "h_bill"=>'',
                "m_bill"=>''
            );
        }
        
        echo json_encode($retData);
    }

    public function orderData(){
        $user_id = $_SESSION['user'];
        $retData = array();

        $Order = Model\Order::getInstance();
        $orderData = $Order->getOrderData($user_id);
        
        foreach($orderData as $value){
            $retData['data'][] = array(
                "order_number" => $value->order_number,
                "ship_num" =>'',
                "order_date"=>date_format(date_create($value->eta),"d/m/Y"),
                "pre_advice"=>'',
                "buyer"=>$value->buyer,
                "supplier"=>'',
                "transport_mode"=>$value->trans_mode,
                "container_mode"=>'',
                "goods_origin"=>$value->port_origin,
                "good_destination"=>'',
                "load_port"=>$value->port_load,
                "discharge_port"=>'',
                "packs"=>'',
                "type"=>'',
                "volume"=>$value->total_volume,
                "uv"=>'',
                "weight" =>$value->total_weight,
                "uw"=>$value->weight_unit,
                "req_stock"=>'',
                "req_work"=>'',
                "h_bill"=>'',
                "m_bill"=>''
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
            $retData['data'][] = array(
                "order_number" => $value->order_number,
                "ship_num" =>'',
                "order_date"=>date_format(date_create($value->eta),"d/m/Y"),
                "pre_advice"=>'',
                "buyer"=>$value->buyer,
                "supplier"=>'',
                "transport_mode"=>$value->trans_mode,
                "container_mode"=>'',
                "goods_origin"=>$value->port_origin,
                "good_destination"=>'',
                "load_port"=>$value->port_load,
                "discharge_port"=>'',
                "packs"=>'',
                "type"=>'',
                "volume"=>$value->total_volume,
                "uv"=>'',
                "weight" =>$value->total_weight,
                "uw"=>$value->weight_unit,
                "req_stock"=>'',
                "req_work"=>'',
                "h_bill"=>'',
                "m_bill"=>''
            );
        }
        
        echo json_encode($retData);
    }
}