<?php

namespace Chat;

class User {
    public $name;
    public $hash;
    protected $session;

    public function __construct($session) {
        $this->session = $session;
    }

    public function auth($name, $password) {
        $this->name = $name;
        $this->hash = crypt($name . $password, CRYPTO_SALT);
    }

    public function sendMessage($message) {
        $this->session->send($this, $message);
    }
}