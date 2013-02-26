<?php
/**
 * =============================================================================
 * @file
 * index.php
 * 
 * Single Entry Point (SEP) for our framework every request to the frameworks 
 * should be done through this file. In that case it's easer to handle site 
 * maintanance modes and similar stuff
 * 
 * The APP constant defines the folder where the application is installed.
 * You can have more than one application working on the same Firestarter core 
 * and you can point the request by either pointing to another SEP from your 
 * .htaccess file or put IF statements in this file to load another application
 * on some condition
 * 
 * The CORE constant defines the folder where the core of the framework resides
 * 
 * Both APP and CORE can reside in other folder than the servers document root 
 * only this SEP file is required to be available in the document root directory
 * 
 * If you dont want to use the controller logic you need to use App::init() to
 * activate the system
 * 
 * Ex:
 * 
 *   define('APP', 'app/');
 *
 *   define('CORE','core/');
 *
 *   include CORE.'core.php';
 *
 *   App::init('AppConfig');
 * 
 */

    define('APP', 'App/');
    
    define('CORE','RK/');

    include CORE.'core.php';

    App::bootstrap();