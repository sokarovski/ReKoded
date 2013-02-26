<?php

namespace RE;

class Request {
    
    /**
     *
     * @var boolean weather or not magic_quotes_gps is set to on or off
     */
    public $gpc = NULL;
    
    /**
     *
     * @var Object representing the current logged in user by the Auth class
     */
    public $u = NULL;
    
    public $s = NULL;
    
    /**
     * @var $f ConcatenatorConfig represents the concatenator object
     */
    
    /**
     * @var $d DBInterface the default database or the first database in the configuration
     */
    
    function __construct() {
	$this->gpc = get_magic_quotes_gpc();
        session_start();
        $this->s = &$_SESSION;
    }
    
    function __get($name) {
	if (in_array($name,array('g','p','i','c','r'))) {
	    $this->g = array();
	    $this->p = array();
	    $this->i = array();
	    $this->c = array();
	    $this->r = array();
	    $this->parseVars($_GET, $this->g, $this->r);
	    $this->parseVars($_POST, $this->p, $this->r);
	    $this->parseSegments();
	    $this->parseVars($_COOKIE, $this->c);
	    return $this->$name;
	} else if ($name == 'segments') {
	    $this->segments = array();
	    $segments = @$_GET['r'];
	    $segments = trim($segments, '/');
	    $segments = explode('/', $segments);
	    $this->segments = $segments;
	    return $this->segments;
	} else if ($name == 'v') {
	    $this->v = $this->beans->getFirstInstance();
	    return $this->v;	    
	} else if ($name == 'm') {
	    $this->m = new Metrics();
	    return $this->m;
	} else if ($name == 'f') {
	    $this->f = new Conc();
	    return $this->f;
	} else if ($name='route') {
            $this->segments;
            $segments = &$this->segments;
            $route = array_shift($segments);
            return strtolower($route);
        }
    }

    /**
     * Parses the input parameters GET,POST,COOKIE into the Input class and also
     * test for the magic quotes
     * 
     * @param array $from which element can be $_GET $_POST or $_COOKIE
     * @param type $to reference to an array where to save the values 
     * @param type $to2 reference to the array that holds all the request variables
     */
    private function parseVars($from, &$to, &$to2=NULL) {
	foreach ($from as $k => $v) {
	    if (is_array($v)) {
		$this->parseVars($v, $to[$k], $to2[$k]);
	    } else {
		$to[$k] = $this->gpc ? stripslashes($v) : $v;
		if ($to2 !== NULL)
		    $to2[$k] = $this->gpc ? stripslashes($v) : $v;
	    }
	}
    }
    
    /**
     * 
     */
    private function parseSegments() {
	$count = 0;
	foreach ($this->segments as $key => $val) {
	    if ($count % 2 == 0) {
		$this->i[$val] = @$this->segments[$key + 1];
		$this->r[$val] = &$this->i[$val];
	    }
	    $count++;
	}
    }
    
    
}