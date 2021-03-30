<?php

namespace Controller;

class Key extends Controller {
    public function __construct() {
        $this->view = new \View\Key;
    }

    public function index() {
        if( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
            
            if( \Session::getInstance()->postSet() ) {

                $_POST['room'] = str_replace('/', '', $_POST['room']);
                $_POST['room'] = str_replace('\\', '', $_POST['room']);

                foreach( \Session::getInstance()->auth_fields as $field ) {
                    setcookie($field, $_POST[$field]);
                }

                extract($_POST);

                $session = new \Chat\Session($key, $room);

                $user = new \Chat\User($session);
                $user->auth($username, $password);

                \Logger::getInstance($session)->put($user, $room);

                $this->redirect('/');

            } else {
                $this->view->set('error', 'Erro!!', True);
                $this->view->render();

            }
            
        } else {
            $this->view->render();

        }
    }
}