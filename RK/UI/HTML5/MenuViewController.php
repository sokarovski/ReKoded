<?php

namespace RE\UI\HTML5;

class MenuViewController extends \RE\UI\ViewController {
    
    public function __construct($parent) {
        parent::__construct($parent);
    }

    public $items = array();
    
    public function addItem($item,$uri) {
        
        $this->items[$item] = $uri;
        
    }
    
}
