<?php

foreach( [&$_GET, &$_POST, &$_COOKIE] as &$global ) {
    if( isset($global) ) foreach( $global as $key => $value ) {
        $sanitized = htmlentities($value);
        $sanitized = str_replace(',', '&comma;', $sanitized);

        $global[$key] = $sanitized;
    }
}