<?php
/**
 * Starts the main timer
 */
$timers = array('main' => microtime(true));

/**
 * Compresses the output if the browser accepts gzip
 */
//ob_start("ob_gzhandler");

/**
 * Includes th Error handler base class and sets php to use is as error and 
 * exception handler
 */
//include(CORE.'libraries/Error.php');
//set_error_handler(array('Error', 'errorHandling'));
//set_exception_handler(array('Error','exceptionHandler'));

/**
 * Checks if the libraries cache files exists for autoloder and includes it 
 * if does not exist it creates it and saves it
 */
if (file_exists(APP.'/cache/libraries.php')) {
    include(APP.'/cache/libraries.php');
} else {
    include_once(CORE.'/LibraryBuilder.php');
    $l = new LibraryBuilder();
    $libraries = $l->buildAndSave();
    unset($l);
}

/**
 *
 * @param string $classname The class that needs to be autoloaded
 */
function __autoload($classname) {
    if (isset($GLOBALS['libraries'][$classname])) {
	include_once($GLOBALS['libraries'][$classname]);
    }
}

/**
 * Includes the core miscelaneous functions
 */
include(CORE.'misc.php');