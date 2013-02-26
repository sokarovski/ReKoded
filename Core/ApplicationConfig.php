<?php
/**
 * =============================================================================
 * @file core/ApplicationConfig.php
 * 
 * This class is a base class for configuring application instance it contains
 * paths and other information needed for the application to work properly
 */

class ApplicationConfig {

    /**
     * @var boolean Weather is in production or development enviroment
     */
    public $production = false;
    
    /**
     *
     * @var string The host on which this application resiedes
     * If you leave it empty or NULL the system will try to figure it out
     */
    public $host = NULL;
    
    /**
     *
     * @var string The folder in document root where your application is set
     * Ex: if your url is	http://localhost/myapp/ 
     * and document root is	/var/www/
     * and application is in	/var/www/myapp/
     * this should be		/myapp/
     * If you leave it to NULL the system will try to figure it our
     * 
     */
    public $basePath = NULL;
    
    /**
     *
     * @var string the SEP base file. If you use .htaccess and fancy urls you
     * you should put empty string in this field
     */
    public $baseFile = 'index.php';
    
    public $suffix = NULL;
    
    /**
     *
     * @var string Publicly availble URL where the static files for this 
     * application are. If you leave it empty the system will try to figure it
     * out assuming that they are in the APP/files folder
     */
    public $files = NULL;
    
    /**
     *
     * @var string Filesystem path where the files for this application are. If
     * you leave it empty the system will try to figure it out. 
     */
    public $filesFs = NULL;
    
    /**
     *
     * @var string the name of the default template to use for rendering 
     */
    public $template = 'default';
    
    /**
     *
     * @var string default controller that needs to be accesed if no controller
     * is specified. In other words this is the Home Page
     */
    public $defaultController = 'home';
    
    /**
     *
     * @var string default method if no method is specifid for a controller
     */
    public $defaultMethod = 'index';
    
    
/**
 * =============================================================================
 * FROM HERE DOWN THE VARIABLES ARE SET BY THE SYSTEM
 * =============================================================================
 */    
    
    /**
     *
     * @var string this is automatically set by the system it should be used 
     * when building urls for links 
     * Ex: <a href="<?php echo App::$c->url; ?>conttroller/method">Link</a>
     */
    public $url = '';
    
    /**
     *
     * @var string this is automatically set by the application. If baseFile is
     * empty this would be empty too. If you have baseFile this will be equal to
     * "?r=". It helps the application to build URLs for links
     */
    public $uriConcatenator = '';
    
    /**
     *
     * @var string the currently active controller found by the 
     * Input::parseRoute function
     */
    public $controller;
    
    /**
     *
     * @var string the currently active method found bt the Input::parseRoute
     */
    public $method;
    
    /**
     *
     * @var string Publicly availble URL where the currently selected template 
     * files are.
     */
    public $viewFiles;
    
    /**
     *
     * @var string Filesystem path where the files for this currently selected 
     * template are
     */
    public $viewFilesFs;
    
    /**
     *
     * @var string Folder where the currently selected template hold the views.
     */
    public $views;
    
    
    /**
     *
     * @var Array hold aliases for requests if the first URI segment is found 
     * in the routes array then it's definition would be used as controller
     * and method.
     * 
     * Ex: if you have route		'something' => 'mycontroller/mymethod'
     * and you request			/something/is/the/other/thing/
     * then mycontroller and my method will be used for request and the uri 
     * segments will contain two filds:  is=the,other=thing
     */
    public $routes = array();    
    
    public function __construct() {
	
	if ($this->production) {
	    error_reporting(0);
	} else {
	    error_reporting(E_ALL & ~E_NOTICE);
	}
	
	
	if ($this->host==NULL) {
	    $this->host = 'http://'.$_SERVER['SERVER_NAME'];
	}
	
	if ($this->basePath === NULL) {
	    $cwd = str_replace('\\','/',getcwd());
	    $root = str_replace('\\','/',$_SERVER['DOCUMENT_ROOT']);
	    $path = str_replace($root, '', $cwd);
	    $path = trim($path,'/\\');
	    $this->basePath = '/'.$path.'/';	    
	}
	
	if ($this->basePath == "") {
	    $this->basePath = "/";
	}
	
	$this->url = $this->basePath;
	if ($this->baseFile != '') {
	    $this->uriConcatenator = '&';
	    $this->url.=$this->baseFile.'?r=';
	} else {
	    $this->uriConcatenator = '?';
	}
	
	if ($this->files === NULL) {
	    $this->files = $this->basePath.APP.'files/';
	}

	if ($this->filesFs === NULL) {
	    $this->filesFs = APP.'files/';
	}

	$this->setTemplate($this->template);
	
    }    
    
    public function setTemplate($template) {
	$this->template = $template;
	$this->viewFiles = $this->files .'views/'.$template.'/';
	$this->viewFilesFs = $this->filesFs .'views/'.$template.'/';
	$this->views = APP.'/views/'.$template.'/';
    }
    
}