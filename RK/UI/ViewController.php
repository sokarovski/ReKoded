<?php 

namespace RE\UI;

class ViewController {
    
    private $view = null;
    protected $parent = null;
    protected $catchers = array();
    
    public function __construct($parent) {
        $this->parent = $parent;
    }

    public function toHTML() {
        
        extract(get_public_object_vars($this));
        
        ob_start();
        
        if ($this->view != null && isset($GLOBALS['__repository']['views'][$this->view.'.view.php'])) {

            include($GLOBALS['__repository']['views'][$this->view.'.view.php']);
            
        } else if ( ($__view = $this->calledClassView()) ) {

            include($__view);
            
        }
        
	$content = ob_get_contents();
	ob_get_clean(); 
        
        return $content;
        
    }
    
    private function calledClassView() {
        
        $cc = get_called_class();
        $cc = explode('\\', $cc);
        $view = array_pop($cc).'.view.php';
        
        return isset($GLOBALS['__repository']['views'][$view]) ? $GLOBALS['__repository']['views'][$view] : false;
        
    }
    
    public function setView($view) {
        $this->view = $view;
    }
    
    public function toArray() {
        
        $obj = get_public_object_vars($this);
        
        foreach($obj as $var=>$val) {
            if ($val instanceof ViewController) {
                $obj[$var] = $val->toArray();
            }
        }
        
        return $obj;
        
    }
    
    public function toJSON() {
        
        $vars = get_public_object_vars($this);
        
        foreach($vars as $vk=>$vv) {
            if ($vv instanceof ViewController) {
                $vars[$vk] = $vv->toArray();
            }
        }
        
        return json_encode($vars);
        
    }
    
    public function output($type=null) {
        
        if ($type == 'index.html') {
            return $this->toHTML();
        }
        
        if ($type == 'index.json') {
            return $this->toJSON();
        }
        
        if ($type == 'index.xml') {
            return $this->toXML();
        }
            
    }
    
    public function trigger($event, $data) {
        if ($this->parent) {
            $this->parent->bubble($event, $data);
        }
    }
    
    private function bubble($event, $data) {
        if ($this->catchers[$event]) {
            call_user_func($this->catchers[$event], $data);
        }
        $this->trigger($event, $data);
    }
    
    public function listen($event, $callable) {
        $this->catchers[$event] = $callable;
    }
    
}