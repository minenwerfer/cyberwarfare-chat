<?php

namespace Chat;

class Session extends Stream {
    public $fields = [
        'name',
        'hash',
        'fgColor',
        'bgColor',
        'content',
        'date'
    ];

    public function __construct($key, $filename) {
        parent::__construct($key, $filename);
    }

    public function send($user, $content) {
        $this->write([
            $user->name,
            $user->hash,
            $user->fgColor,
            $user->bgColor,
            $content,
            date('j/n/Y G:i\\h')
        ]);
    }
}