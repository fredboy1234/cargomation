<?php

namespace App\Controller;

use App\Core;
use App\Core\Model as CoreModel;
use App\Model;
use App\Utility;
use App\Presenter;

/**
 * Profile Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class Profile extends Core\Controller {

    /**
     * Index: Renders the profile view. NOTE: This controller can only be
     * accessed by unauthenticated users!
     * @access public
     * @example profile/index/{$1}
     * @param string $user [optional]
     * @return void
     * @since 1.0.4
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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user);

        if(empty($role->role_name)) {
            Utility\Redirect::to(APP_URL . $role->role_name);
        }

        $User->putUserLog([
            "user_id" => $user,
            "ip_address" => $User->getIPAddress(),
            "log_type" => 7,
            "log_action" => "Access profile",
            "start_date" => date("Y-m-d H:i:s"),
        ]);

        $selectedTheme = $User->getUserSettings($user);
        $dashboardTheme = '';
        if(isset($selectedTheme[0])){
            $selectedTheme = $selectedTheme[0]->theme;
            $dashboardTheme=json_decode($User->getUserSettings($user)[0]->dashboard);
        }else{
            $selectedTheme = '';
        }
        
        
        // Set any dependencies, data and render the view.
        // $this->View->addCSS("css/custom.css");
        // $this->View->addJS("js/custom.js");
        $this->View->addJS("js/profile.js");
        $this->View->addCSS("css/profile.css");
        $this->View->addCSS("css/theme/".$selectedTheme.".css");
        $this->View->addCSS("css/".$selectedTheme.".css");

        $imageList = (Object) Model\User::getProfile($user);
        $profileImage = '/img/default-profile.png';
        $miscImage = '/img/default-profile.png';
        $miscFooter = '/img/default-profile.png';
        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='profile' ){
                $profileImage = base64_decode($img->image_src);
            }
        }

        foreach($imageList->user_image as $img){
            if( $img->image_src!="" && $img->image_type=='Header' ){
                $miscImage  = base64_decode($img->image_src);
            }
            if( $img->image_src!="" && $img->image_type=='Footer' ){
                $miscFooter  = base64_decode($img->image_src);
            }
        }
        
        $this->View->renderTemplate("/profile/index", [
            "title" => "Profile",
            "data" => (new Presenter\Profile($User->data()))->present(),
            "user" => (Object) Model\User::getProfile($user),
            "user_info" => Model\User::getProfile($user)['user_info'][0],
            "contact_list" => $User->getUserContactList($user),
            "image_profile" => $profileImage,
            "miscImage" => $miscImage,
            "miscFooter" => $miscFooter,
            "role" => $role,
            "themes" => Model\User::getUserTheme(),
            "selectedTheme" => $User->getUserSettings($user),
            "user_settings" => $User->getUserSettings($user),
            "notifications" => Model\User::getUserNotifications($user),
            "menu" => Model\User::getUserMenu($role->role_id),
            "dashtheme"=>$dashboardTheme,
        ]);
    }

    /**
     * Update user profile in settings
     * @param null
     */
    public function updateProfile($user=""){
        
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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user);

        if(empty($role->role_name)) {
            Utility\Redirect::to(APP_URL . $role->role_name);
        }

        if(isset($_POST)){
            $User->updateUserInfo(array(
                "first_name"=>$_POST['firstname'],
                "last_name"=>$_POST['lastname'],
                // "email"=>$_POST['email'],
                "phone"=>$_POST['contact'],
                "address"=>$_POST['address'],
                "city"=>$_POST['city'],
                "postcode"=>$_POST['zipcode'],
            ),$_POST['info_id']);
            $User->updateUserProfile(array(
                // "email"=>$_POST['email'],
                "first_name"=>$_POST['firstname'],
                "last_name"=>$_POST['lastname'],
            ),$user);
            $result['status'] = "success";
        } else {
            $result['status'] = "error";
            $result['message'] = "No post data submitted!";
        }

        echo json_encode($result);
        exit;
        
    }

    public function profileImageList($user_id = ""){ 

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // If no user ID has been passed, and a user session exists, display
        // the authenticated users profile.
        if (!$user_id) {
            $userSession = Utility\Config::get("SESSION_USER");
            if (Utility\Session::exists($userSession)) {
                $user_id = Utility\Session::get($userSession);
            }
        }

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user_id)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }
        
        $this->View->renderWithoutHeaderAndFooter("/profile/profileImageList", [
            "user" => Model\User::getProfile($user_id)['user_image']
        ]);
    }

    public function miscImageList($user_id = ""){ 

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // If no user ID has been passed, and a user session exists, display
        // the authenticated users profile.
        if (!$user_id) {
            $userSession = Utility\Config::get("SESSION_USER");
            if (Utility\Session::exists($userSession)) {
                $user_id = Utility\Session::get($userSession);
            }
        }

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user_id)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user_id)->role_name;

        if(empty($role)) {
            Utility\Redirect::to(APP_URL . $role);
        }
        
        $this->View->renderWithoutHeaderAndFooter("/profile/miscImageList", [
            "user" => Model\User::getProfile($user_id)['user_image']
        ]);
    }

    public function insertUserProfile($user=""){
        
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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user);

        if(empty($role->role_name)) {
            Utility\Redirect::to(APP_URL . $role->role_name);
        }
        
        if(isset($_POST)){
            $imageType = $_POST['imageType'];
            $User->inserUserImages(array(
                'user_id' => $user,
                'image_type' => $imageType,
                'image_src' => $_POST['image_src'],
                'imageId'=>$_POST['imageID']
            ),$user);
            
            Utility\Redirect::to(APP_URL . "/profile");
        }
    }

    public function savetheme($user=""){
        
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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!$Role = Model\Role::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        $role = $Role->getUserRole($user);

        if(empty($role->role_name)) {
            Utility\Redirect::to(APP_URL . $role->role_name);
        }

        if(isset($_POST)){
            $User->addUserTheme(array(
                'theme' => $_POST['theme'],
                'user' => $user
            ),$user);
            Utility\Redirect::to(APP_URL . "/profile");
        }else{
            Utility\Redirect::to(APP_URL . "/profile");
        }
        
    }

    public function saveSettings($user = ""){
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

        // Get an instance of the user model using the user ID passed to the
        // controll action. 
        if (!$User = Model\User::getInstance($user)) {
            Utility\Redirect::to(APP_URL);
        }

        if (!empty($_POST)) {
            $column = $_POST['column'];
            if(!isset( $_POST['dash'])){
                unset($_POST['column']);
            }
            $data = $_POST;

            $User->updateUserSettings($column, $data, $user);
            # Utility\Redirect::to(APP_URL . "/profile#settings");
            $result['status'] = "success";
        } else {
            $result['status'] = "error";
            $result['message'] = "No post data submitted!";
        }

        echo json_encode($result);
        exit;
    }

}
