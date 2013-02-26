<?php

namespace RE\HTML5;

class MenuViewController extends \RE\ViewController {
    
    public function __construct($parent) {
        parent::__construct($parent);
    }

    public $items = array();
    
    public function addItem($item,$uri) {
        
        $this->items[$item] = $uri;
        
    }
    
}
