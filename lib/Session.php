<?php

class Session {
    protected static $instance;
    public $auth_fields = ['room', 'key', 'username', 'password'];

    protected $privileged_stream;
    protected $banned_stream;

    public $privileged_list;
    public $banned_list;

    public function __construct() {
        $privileged_fname = SETTINGS_PATH . '/admins.txt';
        $banned_fname = SETTINGS_PATH . '/banned.txt';
        
        $privileged_sz = filesize($privileged_fname);
        $banned_sz = filesize($banned_fname);

        $this->privileged_stream = fopen($privileged_fname, 'a+');
        $this->banned_stream = fopen($banned_fname, 'a+');

        $this->privileged_list = $privileged_sz > 0
            ? explode('\n', fread($this->privileged_stream, filesize($privileged_fname)))
            : [];
            
        $this->banned_list = $banned_sz > 0
            ?explode('\n', fread($this->banned_stream, filesize($banned_fname)))
            : [];
    }

    public static function getInstance() {
        if( !isset(self::$instance) ) {
            self::$instance = new \Session;
        }

        return self::$instance;
    }

    public function cookiesSet() {
        $result = array_filter($this->auth_fields, function($field) {
            return !isset($_COOKIE[$field]) || empty($_COOKIE[$field]);
        });

        return sizeof($result) === 0;
    }

    public function postSet() {
        $result = array_filter($this->auth_fields, function($field) {
            return !isset($_POST[$field]) || empty($_POST[$field]);
        });

        return sizeof($result) === 0;
    }

    public function get() {
        return [
            $_COOKIE['room'],
            $_COOKIE['key'],
            $_COOKIE['username'],
            $_COOKIE['password']
        ];
    }

    public function destroy() {
        foreach( $this->auth_fields as $field ) {
            unset($_COOKIE[$field]);
            setcookie($field, '', time() - 3600);
        }
    }

    public function isPrivileged($uhash) {
        return sizeof(array_filter($this->privileged_list, function( $hash ) use( $uhash ) {
            return trim($hash) === $uhash;

        })) > 0;
    }

    public function isBanned($hash) {
        return sizeof(array_filter($this->banned_list, function( $iphash ) use( $hash ) {
            return trim($iphash) === $hash;

        })) > 0;
    }

    public function ban($iphash) {
        if( !$this->isBanned($iphash) ) {
            fwrite($this->banned_stream, "$iphash\n");
        }
    }

    public function unban($iphash) {
        ftruncate($this->banned_stream, 0);

        foreach( $this->banned_list as $banned ) {
            if( trim($banned) !== $iphash ) {
                fwrite($this->banned_stream, "$banned\n");
            }
        }
    }

    public function promote($hash) {
        if( !$this->isPrivileged($hash) ) {
            fwrite($this->privileged_stream, "$hash\n");
        }
    }

    public function demote($hash) {
        ftruncate($this->privileged_stream, 0);

        foreach( $this->privileged_list as $privileged ) {
            if( trim($privileged) !== $hash ) {
                fwrite($this->privileged_stream, "$privileged\n");
            }
        }
    }
}