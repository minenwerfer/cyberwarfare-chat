<?php

namespace Chat;

class User {
    public $name;
    public $hash;
    public $iphash;
    public $fgColor;
    public $bgColor;
    public $session;

    protected $privileged;
    protected $banned;

    public function __construct($session) {
        $this->session = $session;
    }

    public function auth($name, $password) {
        $this->name = $name;
        $this->hash = crypt($name . $password, CRYPTO_SALT);
        $this->iphash = crypt($_SERVER['REMOTE_ADDR'], CRYPTO_SALT);

        $this->fgColor = substr(md5($this->hash), 0, 6);
        $this->bgColor = \Template::contrastColor($this->fgColor);

        $this->privileged = $this->getPrivilege();
        $this->banned = $this->getBan();

        if( $this->banned ) {
            throw new \Exception('you are banned', 100);
        }
    }

    protected function getPrivilege() {
        return \Session::getInstance()->isPrivileged($this->hash);
    }

    protected function getBan() {
        return \Session::getInstance()->isBanned($this->iphash);
    }

    public function isPrivileged() {
        return $this->privileged;
    }

    public function isBanned() {
        return $this->banned;
    }

    public function getDisplay() {
        return "$this->name:$this->iphash";
    }

    public function sendMessage($message, $secure = False) {
        $sanitized = $message;
        if( $secure ) {
            // $sanitized = htmlentities($sanitized);
            $sanitized = str_replace(',', '&comma;', $sanitized);
        }

        $this->session->send($this, $sanitized);

        if( strpos($message, '!') === 0 ) {
            @list($command, $arg) = explode(' ', $message, 2);
            \Command::getInstance()->issueCommand($this, $command, $arg);

        }
    }

    public function secureSendMessage($message) {
        $this->sendMessage($message, True);
    }
}