<?php

namespace Chat;

class Crypto {
    protected $ciphering;
    protected $iv;
    protected $ivLength;
    protected $key;
    protected $options;

    public function __construct($key) {
        $this->ciphering = 'AES-128-CTR';
        $this->iv = CRYPTO_IV;
        $this->ivLength = openssl_cipher_iv_length($this->ciphering);
        $this->key = $key;
        $this->options = 0;
    }

    public function encrypt($data) {
        return openssl_encrypt(
            $data,
            $this->ciphering,
            $this->key,
            $this->options,
            $this->iv
        );
    }

    public function decrypt($data) {
        return openssl_decrypt(
            $data,
            $this->ciphering,
            $this->key,
            $this->options,
            $this->iv
        );
    }
}