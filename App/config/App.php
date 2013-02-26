<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of App
 *
 * @author psokarovski
 */
class App extends RE\Application {
    
    static $defaultRoute = 'home';
    
    static $map = array(
        'home' => 'HomeViewController'
    );
    
}
