<?php

class PluginManager {
    protected static $instance;
    protected $plugins = [];

    public static function getInstance() {
        if( !isset(self::$instance) ) {
            self::$instance = new \PluginManager;
        }

        return self::$instance;
    }

    public function load($mixed) {
        if( is_array($mixed) ) {
            foreach( $mixed as $class ) {
                $this->plugins[] = $class;
                $class::install();
            }
        }
    }

    public function getPlugins() {
        return $this->plugins;
    }
}