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

    public function set($key, $value, $parent = False) {
        $this->values[$key] = $value;

        if( $parent ) {
            $this->layout->set($key, $value);
        }

        $this->layout->set('body', $this->output());
    }

    public function render() {
        echo $this->layout->render();
    }
}