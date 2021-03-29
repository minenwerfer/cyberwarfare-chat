<?php

namespace View;

class Home extends Layout {
    public function __construct() {
        parent::__construct('home/index', 'CyberWarfare Chat (CWC)');

        $this->set([
            'update_uri'    => $this->makeURI('/?c=Home&' . rand()),
            'logout_uri'    => $this->makeURI('/?m=logout'),
            'send_uri'      => $this->makeURI('/?m=send'),
            'ciphering'     => \Chat\Crypto::$ciphering,

            'notice'        => \Plugin\PCore::getNotice(),
            'user_script'   => ''
        ]);
    }
}