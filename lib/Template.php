<?php

class Template {
    protected $file;
    protected $values = [];

    public function __construct($file) {
        $this->file = __DIR__ . '/View/templates/' . $file . '.html';
    }

    public function setMultiple($array) {
        foreach( $array as $key => $value ) {
            $this->values[$key] = $value;
        }
    }

    public function set($mixed, $value = NULL) {
        if( is_array($mixed) ) {
            $this->setMultiple($mixed);
        } else {
            $this->values[$mixed] = $value;
        }
    }

    public function output() {
        if( !file_exists($this->file) ) {
            return "Error loading template file {$this->file}";
        }

        $output = file_get_contents($this->file);
        
        foreach( $this->values as $key => $value ) {
            $replaceExp = "{{ $key }}";
            $output = str_replace($replaceExp, $value, $output);
        }

        return $output;
    }

    public function render() {
        echo $this->output();
    }

    protected function makeURI($uri) {
        return str_replace('//', '/', BASE_URI . $uri);
    }

    public static function contrastColor($hexcolor) {
        $r = hexdec(substr($hexcolor, 0, 2));
        $g = hexdec(substr($hexcolor, 2, 2));
        $b = hexdec(substr($hexcolor, 4, 2));

        $yiq = (($r * 299) + ($g * 587) + ($b * 144)) / 1000;

        return ($yiq >= 128) ? '#000000' : '#dddddd';
    }
}