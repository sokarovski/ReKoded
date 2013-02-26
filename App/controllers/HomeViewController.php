<?php

class HomeViewController extends RE\HTML5\PageViewController {
    
    /**
     *
     * @var menuview;
     */
    public $menu = null;
    
    /**
     *
     * @var showarray
     */
    public $content = null;
    
    public function __construct() {
        
        $this->listen('title', array($this, 'handleTitle'));
        $this->setView('HomeView');
        
        $this->menu = new RE\HTML5\MenuViewController($this);
        $this->menu->addItem('List1', 'home');
        $this->menu->addItem('List2', 'home/arr/2/');
        
        $this->content = new ShowArrayViewController($this);
        
        if (App::$request->i['arr'] != '2') {
            $this->content->listData = array(1,2,3,4);
            $this->content->setListTitle('List1');
        } else {
            $this->content->listData = array('Item 1', 'Item 2', 'Item 3', 'Item 4');
            $this->content->setListTitle('List2');
        }
        
    }
    
    public function handleTitle($data) {
        $this->title = $data;
    }
    
}
