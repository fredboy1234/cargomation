<?php

namespace App\Controller;

use App\Core;
use App\Model;
use App\Utility;

use App\Core\View;

/**
 * Index Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class Client extends Core\Controller {

    /**
     * Index: Renders the index view. NOTE: This controller can only be accessed
     * by authenticated users!
     * @access public
     * @example index/index
     * @return void
     * @since 1.0
     */
    public function index() {

        // Check that the user is authenticated.
        Utility\Auth::checkAuthenticated();

        // Get an instance of the user model using the ID stored in the session. 
        $userID = Utility\Session::get(Utility\Config::get("SESSION_USER"));
        if (!$User = Model\User::getInstance($userID)) {
            Utility\Redirect::to(APP_URL);
        }

        $this->initExternals();
 
        // Set any dependencies, data and render the view.
        $this->View->addCSS("css/google_font.css");
        $this->View->addCSS("css/custom.css");
        $this->View->addJS("js/custom.js");

        // $this->View->render("client/index", [
        //     "title" => "Index",
        //     "user" => $User->data()
        // ]);

        $this->View->renderTemplateTwig("/client/index.php", [
            "title" => "Client"
        ]);

        //Core\View::renderTemplate('client/index.html');
    }

    public function profile(){
        $this->initExternals();
 
        // Set any dependencies, data and render the view.
        $this->View->addCSS("css/google_font.css");
        $this->View->addCSS("css/custom.css");
        $this->View->addJS("js/custom.js");

        $this->View->renderTemplateTwig("/client/index.php", [
            "title" => "Client"
        ]);
    }

    public function userLogs(){
        $this->initExternals();
 
        // Set any dependencies, data and render the view.
        $this->View->addCSS("css/google_font.css");
        $this->View->addCSS("css/custom.css");
        $this->View->addJS("js/custom.js");

        $this->View->renderTemplateTwig("/client/index.php", [
            "title" => "Client"
        ]);
    }

    public function initExternals() {
        $this->View->addCSS("bower_components/admin-lte/plugins/fontawesome-free/css/all.min.css");
        $this->View->addCSS("bower_components/admin-lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css");
        $this->View->addCSS("bower_components/admin-lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css");
        $this->View->addCSS("bower_components/admin-lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css");
        // $this->View->addCSS("bower_components/admin-lte/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.css");
        $this->View->addCSS("bower_components/admin-lte/plugins/toastr/toastr.min.css");
        $this->View->addCSS("bower_components/admin-lte/dist/css/adminlte.css");

        $this->View->addJS("bower_components/admin-lte/plugins/jquery/jquery.min.js");
        $this->View->addJS("bower_components/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js");
        $this->View->addJS("bower_components/admin-lte/plugins/datatables/jquery.dataTables.min.js");
        $this->View->addJS("bower_components/admin-lte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js");
        $this->View->addJS("bower_components/admin-lte/plugins/datatables-responsive/js/dataTables.responsive.min.js");
        $this->View->addJS("bower_components/admin-lte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js");
        $this->View->addJS("bower_components/admin-lte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js");
        // $this->View->addJS("bower_components/admin-lte/plugins/sweetalert2/sweetalert2.min.js");
        $this->View->addJS("bower_components/admin-lte/plugins/toastr/toastr.min.js");
        $this->View->addJS("bower_components/admin-lte/dist/js/adminlte.js");
    }

}
