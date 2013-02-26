<?php 

namespace RE;

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
        if ($this->view != null) {
            include(APP.'views/'.$this->view.'.php');
        } else {
            $cc = get_called_class();
            $cc = explode('\\', $cc);
            $____view = array_pop($cc);
            unset($cc);
            include(CORE.'HTML5/views/'.$____view.'.php');
        }
	$content = ob_get_contents();
	ob_get_clean(); 
        return $content;
    }
    
    public function setView($view) {
        $this->view = $view;
    }
    
    public function toJSON() {
        echo json_encode($this);
    }
    
    public function output() {
        return $this->toHTML();
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