<?php

namespace Chat;

class User {
    public $name;
    public $hash;
    public $fgColor;
    public $bgColor;
    protected $session;

    public function __construct($session) {
        $this->session = $session;
    }

    public function auth($name, $password) {
        $this->name = $name;
        $this->hash = crypt($name . $password, CRYPTO_SALT);

        $this->fgColor = substr(md5($this->hash), 0, 6);
        $this->bgColor = \Template::contrastColor($this->fgColor);
    }

    public function sendMessage($message) {
        $this->session->send($this, $message);
    }
}