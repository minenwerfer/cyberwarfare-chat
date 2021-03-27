<?php

namespace Controller;

class Controller {
    protected $view;

    public function __construct() {
        //
    }

    protected function redirect($uri) {
        $where = BASE_URI . $uri;
        $where = str_replace('//', '/', $where);
        
        header("Location: $where");
    }
}