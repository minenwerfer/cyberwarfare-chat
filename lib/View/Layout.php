<?php

namespace View;

class Layout extends \Template {
    protected $layout;

    public function __construct($template, $title) {
        parent::__construct($template);

        $this->layout = new \Template('layout');
        $this->layout->values = [
            'error' => ''
        ];

        $this->layout->set('title', $title);
        $this->layout->set('body', $this->output()); 
    }

    public function set($mixed, $value = NULL, $parent = False) {
        
        if( is_array($mixed) ) {
            $this->setMultiple($mixed);
        } else {
            $this->values[$mixed] = $value;
        }

        if( $parent ) {
            $this->layout->set($mixed, $value);
        }

        $this->layout->set('body', $this->output());
    }

    public function render() {
        $this->layout->render();
    }
}