<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * Exchange Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class Exchange extends Core\Controller {

    /**
     * Index: Renders the index view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function index() {

        // Get an instance of the user model using the ID stored in the session. 
        $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        if (!$User = Model\User::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }        

        if (!$Role = Model\Role::getInstance($userID)) {
           Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($userID)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }

        $selectedTheme = $User->getUserSettings($userID);

        if(isset($selectedTheme[0]) && !empty($selectedTheme)){
            $selectedTheme = $selectedTheme[0]->theme;
        }else{
            $selectedTheme = 'default';
        }

        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/".$selectedTheme.".css");

        $this->View->addCSS("css/exchange.css");
        $this->View->addJS("js/exchange.js");
        
        // Render view template
        // Usage renderTemplate(string|$template, string|$filepath, array|$data)

        $imageList = (Object) Model\User::getProfile($userID);
        $profileImage = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }

        $list_currency = Model\Exchange::getCurrencyList();
        $currency = Model\Exchange::getAllCurrencyList();
        
        $this->View->renderTemplate($role, $role . "/exchange/index", [
            "title" => "Daily Exchange",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($userID),
            "users" => Model\User::getUsersInstance($userID),
            "image_profile" => $profileImage,
            "dash_photo" =>Model\User::getUsersDashPhoto($userID),
            'selected_theme' => $selectedTheme,
            'list_currency' => $list_currency,
            'currency' => $currency
        ]);

    }

    public function calculate($currency_code = "USD", $arg = "*") {
        $list_currency = Model\Exchange::calcExchange($arg, $currency_code);
        echo json_encode($list_currency);
    }
}