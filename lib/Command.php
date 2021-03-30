<?php

class Command {
    protected static $instance;
    protected $settingsPath = BASE_DIR . '/settings';
    protected $commands = [];

    public static function getInstance() {
        if( !isset(self::$instance) ) {
            self::$instance = new \Command;
        }

        return self::$instance;
    }

    public function addCommand($command, $function, $privileged = True) {
        $this->commands[] = [
            'command'   => $command,
            'function'  => $function,
            'privileged'=> $privileged
        ];
    }

    public function addCommands($array) {
        foreach( $array as $command ) {
            $this->commands[] = $command;
        }
    }

    public function getCommand($name) {
        $command = array_values(array_filter($this->commands, function($command) use($name) {
            return $command['command'] === $name;

        }));

        if( $command ) {
            return $command[0];
        }
    }

    public function getCommands() {
        return $this->commands;
    }

    public function issueCommand($user, $command, $arg) {

        $issue = $this->getCommand($command);
        $privileged = isset($issue['privileged']) && $issue['privileged'] === True;

        if( $issue ) {
            if( $privileged && !$user->isPrivileged() ) {
                throw new \Exception('not enought privileges');
            }

            return call_user_func($issue['function'], $user, $arg);

        } else {
            throw new \Exception('command not found');

        }
    }

    public function sendMessage($user, $message) {
        $user->session->write([
            'Output',
            'system',
            '#00ff00',
            '#000000',
            str_replace(',', '&comma;', $message),
            date(DATE_FORMAT)
        ]);
    }

    public function getUserScript() {
        return @file_get_contents($this->settingsPath . '/user_script.txt');
    }
}