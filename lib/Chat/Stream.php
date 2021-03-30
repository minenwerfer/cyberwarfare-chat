<?php

namespace Chat;

class Stream {
    public $fileName;
    public $crypto;
    protected $fileHandler;
    protected $limit = 150;
    
    public function __construct($key, $fileName) {
        if( !file_exists(STORE_PATH) ) {
            if( !mkdir(STORE_PATH) ) {
                throw new \Exception('unwritable store path', 0);
            }
        }

        if( $fileName ) {
            $this->setFilename($fileName);
            $this->open();
        }

        $this->crypto = new Crypto($key);
    }

    public function __destruct() {
        if( $this->fileHandler ) {
            fclose($this->fileHandler);
        }
    }

    public function setFilename($fileName) {
        $encrypted = \crypt($fileName, CRYPTO_SALT);
        $encrypted = str_replace('/', '_', $encrypted);
        $this->fileName = STORE_PATH . "/$encrypted";
    }

    public function getFilename() {
        return $this->fileName;
    }

    public function open() {
        $this->fileHandler = fopen($this->fileName, 'a+');
        if( !$this->fileHandler ) {
            throw new \Exception('couldnt open chat', 1);
        }
    }

    public function clear() {
        unlink($this->fileName);
    }

    public function checkMessage($data) {
        return strpos($data, SANITY_STR) === 0;
    }

    public function write($dataArray) {
        $messages = $this->readChunk();

        if( ($firstMsg = $messages->current()) ) {
            // file already populated
            // will only write if key matches
            $messages->rewind();
        }

        $data = array_merge([SANITY_STR], $dataArray);
        $data = implode(',', $data);
        $data = $this->crypto->encrypt($data);

        fwrite($this->fileHandler, $data ."\n");
    }

    public function rewind() {
        rewind($this->fileHandler);
    }

    public function readChunk() {
        $counter = 0;

        while( ($line = fgets($this->fileHandler)) !== false ) {

            if( empty($line) ) {
                continue;
            }

            $data = $this->crypto->decrypt($line);
            
            if( !$this->checkMessage($data) ) {
                if( $counter === 0 ) {
                    throw new \Exception('invalid key', 2);
                } else {
                    continue;
                }
            }

            $data = explode(',', $data);
            array_shift($data);

            $counter++;
            yield $data;
        }
    }

    public function readLast() {
        $file = file($this->fileName);

        if( sizeof($file) === 0 ) {
            return;
        }

        $file = array_reverse($file);
        $firstMsg = $this->crypto->decrypt(end($file));

        if( !$this->checkMessage($firstMsg) ) {
            throw new \Exception('invalid key', 2);
        }

        array_splice($file, $this->limit);

        foreach( $file as $line ) {
            $data = $this->crypto->decrypt($line);

            $data = explode(',', $data);
            array_shift($data);

            yield $data;
        }
    }
}