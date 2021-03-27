<?php

namespace Chat;

class Session extends Stream {
    public function __construct($key, $filename) {
        parent::__construct($key, $filename);
    }

    public function send($user, $content) {
        $this->write([
            $user->name,
            $user->hash,
            $content,
            date('j/n/Y G:i\\h')
        ]);
    }
}