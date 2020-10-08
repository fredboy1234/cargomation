<?php

namespace App\Core;

/**
 * Core View:
 *
 * @author John Alex
 * @since 1.0
 */
class View {

    /** @var string The title of the page. */
    protected $title = "";

    /** @var string The HTML used to load in external style sheets. */
    private $_linkTags = "";
    private static $_staticLinkTags = "";

    /** @var string The HTML used to load in external script files. */
    private $_scriptTags = "";
    private static $_staticScriptTags = "";

    public function __construct(){
        View::$_staticLinkTags = $this;
        View::$_staticScriptTags = $this;
    }

    /**
     * Add CSS: Creates the <link> tags, which defines a link between the view
     * file and external style sheets.
     * @access public
     * @param mixed $files
     * @return void
     * @since 1.0
     */
    public function addCSS($files) {
        
        // Cast the value of $files to type array if it is not already.
        if (!is_array($files)) {
            $files = (array) $files;
        }
        foreach ($files as $file) {
            // Check that the file exists in the public directory, creating the
            // <link> tag if it true.
            if (file_exists(PUBLIC_ROOT . "/" . $file)) {
                $this->_linkTags .= '<link type="text/css" rel="stylesheet" href="' . $this->makeURL($file) . '" />' . "\n";
            }
        }
    }

    /**
     * Add Data: Loops through an array of data, setting the key and value as
     * class properties so that it can be accessed in the views HTML.
     * @access public
     * @param array $data
     * @return void
     * @since 1.0
     */
    public function addData(array $data) {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Add JS: Creates the <script> tags, which points to an external script
     * file through the src attribute.
     * @access public
     * @param mixed $files
     * @return void
     * @since 1.0
     */
    public function addJS($files) {
        
        // Cast the value of $files to type array if it is not already.
        if (!is_array($files)) {
            $files = (array) $files;
        }
        foreach ($files as $file) {
            
            // Check that the file exists in the public directory, creating the
            // <script> tag if it true.
            if (file_exists(PUBLIC_ROOT . "/" . $file)) {
                $this->_scriptTags .= '<script type="text/javascript" src="' . $this->makeURL($file) . '"></script>' . "\n";
            }
        }
    }

    /**
     * Escape HTML: Converts all applicable characters to HTML entities.
     * @param string $string
     * @return string
     */
    public function escapeHTML($string) {
        return(htmlentities($string, HTMLENTITIES_FLAGS, HTMLENTITIES_ENCODING, HTMLENTITIES_DOUBLE_ENCODE));
    }

    /**
     * Get CSS: Returns the <link> tags that load in the external style sheets.
     * @access public
     * @return string
     */
    public function getCSS() {
        return($this->_linkTags);
    }

    /**
     * Get File: Requires in a view file if it exists.
     * @access public
     * @param string $filepath
     * @return void
     * @since 1.0
     */
    public function getFile($filepath) {
        $filename = VIEW_PATH . $filepath . ".php";
        if (file_exists($filename)) {
            require $filename;
        }
    }

    /**
     * Get JS: Returns the <script> tags that load in the external script files.
     * @access public
     * @return string
     */
    public function getJS() {
        return($this->_scriptTags);
    }

    /**
     * Make URL: Creates and returns a clean internal URL.
     * @param mixed $path [optional]
     * @return string
     */
    public function makeURL($path = "") {
        if (is_array($path)) {
            return(APP_URL . implode("/", $path));
        }
        return(APP_URL . $path);
    }

    /**
     * Render: Requires in a view file and sets any view data if specified.
     * @access public
     * @param string $filepath
     * @param array $data [optional]
     * @return void
     * @since 1.0
     */
    public function render($filepath, array $data = []) {
        $this->addData($data);
        $this->getFile(DEFAULT_HEADER_PATH);
        $this->getFile($filepath);
        $this->getFile(DEFAULT_FOOTER_PATH);
    }

    /**
     * Render Multiple: Requires in multiple view file and sets any view data if
     * specified.
     * @access public
     * @param array $filepaths
     * @param array $data [optional]
     * @return void
     * @since 1.0
     */
    public function renderMultiple(array $filepaths, array $data = []) {
        $this->addData($data);
        $this->getFile(DEFAULT_HEADER_PATH);
        foreach ($filepaths as $filepath) {
            $this->getFile($filepath);
        }
        $this->getFile(DEFAULT_FOOTER_PATH);
    }

    /**
     * Render Without Header and Footer: Requires in a view file and sets any
     * view data if specified, without the header and footer templates.
     * @access public
     * @param string $filepath
     * @param array $data [optional]
     * @return void
     * @since 1.0
     */
    public function renderWithoutHeaderAndFooter($filepath, array $data = []) {
        $this->addData($data);
        $this->getFile($filepath);
    }

    /**
     * Render Template: Requires in a view file and sets any view data if specified.
     * @access public
     * @param string $filepath
     * @param array $data [optional]
     * @return void
     * @since 1.0
     */
    public function renderTemplate($template, $filepath, array $data = []) {
        $this->addData($data);
        $this->getFile('_template/' . $template . '/header');
        $this->getFile('_template/' . $template . '/navbar');
        $this->getFile('_template/' . $template . '/sidebar');
        $this->getFile('_template/' . $template . '/breadcrumb');
        $this->getFile($filepath);
        $this->getFile('_template/' . $template . '/footer');
    } 

    /**
     * Render a view template using Twig
     * @access public
     * @param string $template  The template file
     * @param array $args  Associative array of data to display in the view (optional)
     * @return void
     * @since 1.0
     */
    public static function renderTemplateTwig($template, array $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\Filesystemloader(VIEW_PATH);
            $twig = new \Twig\Environment($loader);
        }
       
        $cssLinks = new \Twig\TwigFunction("getCss", function () {
            return View::$_staticLinkTags->_linkTags;
        });

        $jsLinks = new \Twig\TwigFunction("getJs", function () {
            return View::$_staticScriptTags->_scriptTags;
        });

        $currentPage = new \Twig\TwigFunction("getCurrentPage", function () {
            return $_SERVER['REQUEST_URI'];
            // return View::displayCurrentPage($url);
        });

        $twig->addFunction($cssLinks);
        $twig->addFunction($jsLinks);
        $twig->addFunction($currentPage);

        echo $twig->render($template, $args);
    }

    public static function displayCurrentPage($dir){
        echo VIEW_PATH . $dir; die;
    }

}
