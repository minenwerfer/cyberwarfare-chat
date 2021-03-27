<?php

namespace View;

class Home extends Layout {
    public function __construct() {
        parent::__construct('home/index', 'Página inicial');
    }
}