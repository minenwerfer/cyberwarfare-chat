<?php

namespace Chat;

class Stream {
    protected $fileName;
    protected $fileHandler;
    protected $crypto;
    protected $limit = 28;
    
    public function __construct($key, $fileName) {
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
        $this->fileName = $fileName;
    }

    public function getFilename() {
        return $this->fileName;
    }

    public function open() {
        $this->fileHandler = fopen($this->fileName, 'a+');
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
            $data = $this->crypto->decrypt($line);
            
            if( !$this->checkMessage($data) ) {
                if( $counter === 0 ) {
                    throw new \Exception('invalid key');
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
        $file = array_reverse($file);
        array_splice($file, $this->limit);

        $counter = 0;
        foreach( $file as $line ) {
            $data = $this->crypto->decrypt($line);

            if( !$this->checkMessage($data) ) {
                if( $counter === 0 ) {
                    throw new \Exception('invalid key');
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
}