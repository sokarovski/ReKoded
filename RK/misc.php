<?php


function get_public_object_vars($obj) {
  return get_object_vars($obj);
}


function str_lreplace($search, $replace, $subject) {
    
    $pos = strrpos($subject, $search);
    
    if ( $pos !== false ) {
        
        $subject = substr_replace($subject, $replace, $pos, strlen($search));
        
    }
    
    return $subject;
}

function p($var) {
    echo '<pre style="font-family:Arial, Helvetica, sans-serif; font-size:12px; border:1px solid #aaaaaa; padding:20px; border-radius:8px; background-color:#f1f1f1; color:#444444;">' . print_r($var, true) . '</pre>';
}

function l($obj) {
    $var = array('log'=>$obj);
    echo '<script> window.phpl = '.  json_encode($var).'; console.log(window.phpl.log); window.phpl = null; </script>';
}