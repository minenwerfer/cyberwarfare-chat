<?php

class Template {
    protected $file;
    protected $values = [];

    public function __construct($file) {
        $this->file = __DIR__ . '/View/templates/' . $file . '.html';
    }

    public function set($key, $value) {
        $this->values[$key] = $value;
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
}