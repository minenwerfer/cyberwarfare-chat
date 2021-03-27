<?php

class Session {
    protected static $instance;
    public $auth_fields = ['room', 'key', 'username', 'password'];

    public function __construct() {
        //
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
}