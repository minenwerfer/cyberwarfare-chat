<?php

class Logger {
    protected static $instance;

    protected $log_stream;
    protected $log_list;
    protected $session;

    public function __construct($chatSession) {
        $this->session = $chatSession;

        $log_fname = $chatSession->fileName . '.log';
        $log_sz = @filesize($log_fname);

        $this->log_stream = fopen($log_fname, 'a+');

        $this->log_list = $log_sz > 0
            ? explode("\n", fread($this->log_stream, $log_sz))
            : [];
    }

    public static function getInstance($opts = NULL) {
        if( !isset(self::$instance) ) {
            self::$instance = new \Logger($opts);
        }

        return self::$instance;
    }

    public function put($user, $room) {
        $dataArray = [
            date(DATE_FORMAT),
            $room,
            $user->name,
            $user->hash,
            $user->iphash
        ];

        $data = array_merge([SANITY_STR], $dataArray);
        $data = implode(',', $data);
        $data = $this->session->crypto->encrypt($data);

        fwrite($this->log_stream, "$data\n");
    }

    public function get($pattern = NULL, $limit = 25) {
        $log = [];
        $count = 0;

        foreach( $this->log_list as $entry ) {

            if( $count++ > $limit ) {
                break;
            }

            $plain = $this->session->crypto->decrypt(trim($entry));
            if( strpos($plain, SANITY_STR) !== 0 ) {
                continue;
            }

            $plain = explode(',', $plain);
            array_splice($plain, 0, 1);

            if( isset($pattern) && !empty($pattern) ) {
                if( !in_array($pattern, $plain) ) {
                    continue;
                }
            }

            $log[] = $plain;
        }

        return $log;
    }

    public function clear() {
        ftruncate($this->log_stream, 0);
    }
}