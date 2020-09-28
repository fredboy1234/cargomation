<?php

namespace App\Core;

use App\Utility;

/**
 * Core Controller:
 *
 * @author John Alex
 * @since 1.0
 */
class Controller {

    /** @var View An instance of the core view class. */
    protected $View = null;

    /**
     * Construct: Creates and stores a new instance of the core view class,
     * which can be accessed by any controller which extends this class.
     * @access public
     * @since 1.0
     */
    public function __construct() {

        // Initialize a session.
        Utility\Session::init();

        // If the user is not logged in but a remember cookie exists then
        // attempt to login with cookie. NOTE: We only do this if we are not on
        // the login with cookie controller method (this avoids creating an
        // infinite loop).
        if (Utility\Input::get("url") !== "login/_loginWithCookie") {
            $cookie = Utility\Config::get("COOKIE_USER");
            $session = Utility\Config::get("SESSION_USER");
            if (!Utility\Session::exists($session) and Utility\Cookie::exists($cookie)) {
                Utility\Redirect::to(APP_URL . "login/_loginWithCookie");
            }
        }

        // Create a new instance of the core view class.
        $this->View = new View;
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     */
    public function __call($name, $args)
    {

        var_dump("TEST"); exit;
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }


    /**
     * Before filter - called before an action method.
     *
     * @return void
     */
    protected function before() {
    }

    /**
     * After filter - called after an action method.
     *
     * @return void
     */
    protected function after() {
    }

}
