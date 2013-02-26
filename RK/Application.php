<?php

namespace RE;

class Application {
    
    static $request;
    static $c;
    
    static $map = array();
    static $defaultRoute = 'home';
    
    static function bootstrapWithRoute($route, $type = 'index.html') {
        
        if (self::$request == null)
            self::$request = new Request();
        
        if (self::$c == null)
            self::$c = new ApplicationConfig();
        
        $acls = get_called_class();
        
        if ($route == '')
            $route = $acls::$defaultRoute;
        
        if (isset($acls::$map[$route])) {
            $cls = $acls::$map[$route];
            $rootViewController = new $cls();
            echo $rootViewController->output($type);
            return;
        }
        
        if (isset($acls::$map['404'])) {
            $cls = $acls::$map['404'];
            $rootViewController = new $cls();
            echo $rootViewController->output($type);
            return;
        }
        
        echo 404;
        return;
        
    }
    
    static function bootstrap() {
        
        self::$request = new Request();
        self::$c = new ApplicationConfig();
        self::bootstrapWithRoute(self::$request->route, self::$request->type);
        
    }
    
}
