<?php

class ShowArrayViewController extends RE\ViewController {
     
    public $listTitle = 'MyList';
    public $listData = array(1,2,3);
    
    public function __construct($parent) {
        
        parent::__construct($parent);
        
        $this->setView('ShowArray');
        
    }
    
    public function setListTitle($title) {
        $this->listTitle = $title;
        $this->trigger('title', $title);
    }
    
}