<?php

$globals = [
    '_GET',
    '_POST',
    '_REQUEST',
    '_COOKIE'
];

foreach( $globals as $global ) {
    if( isset($$global) ) foreach( $$global as $key => $value ) {
        $$global[$key] = htmlentities($value);
    }
}