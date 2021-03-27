<?php

namespace View;

class Home extends Layout {
    public function __construct() {
        parent::__construct('home/index', 'CyberWarfare Chat (CWC)');
        $this->set('logout_uri', $this->makeURI('/?m=logout'));
        $this->set('send_uri', $this->makeURI('/?m=send'));
        $this->set('ciphering', \Chat\Crypto::$ciphering);
    }
}