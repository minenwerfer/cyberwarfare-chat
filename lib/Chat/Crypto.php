<?php

namespace Chat;

class Crypto {
    public static $ciphering = 'AES-128-CTR';
    protected $iv;
    protected $ivLength;
    protected $key;
    protected $options;

    public function __construct($key) {
        $this->iv = CRYPTO_IV;
        $this->ivLength = openssl_cipher_iv_length(self::$ciphering);
        $this->key = $key;
        $this->options = 0;
    }

    public function encrypt($data) {
        return openssl_encrypt(
            $data,
            self::$ciphering,
            $this->key,
            $this->options,
            $this->iv
        );
    }

    public function decrypt($data) {
        return openssl_decrypt(
            $data,
            self::$ciphering,
            $this->key,
            $this->options,
            $this->iv
        );
    }
}