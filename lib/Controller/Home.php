<?php

namespace Controller;

class Home extends Controller {
    protected $session;
    protected $user;

    public function __construct() {
        //
    }

    protected function initChat() {
        if( !\Session::getInstance()->cookiesSet() ) {
            $this->redirect('/?c=Key');
            return False;
        }

        if( $this->session ) {
            return True;
        }

        list(
            $room,
            $key,
            $username,
            $password

        ) = \Session::getInstance()->get();

        try {
            $this->session = new \Chat\Session($key, $room);

            $this->user = new \Chat\User($this->session);
            $this->user->auth($username, $password);

            \Logger::getInstance($this->session);

        } catch( \Exception $error ) {

            if( $error->getCode() === 100 ) {
                echo "You are banned";
                exit;
            }

            if( $error->getCode() !== 2 ) {
                $this->logout();
                exit;
            }

        }

        $this->view = new \View\Home;
        $this->view->set('room', $room);
        $this->view->set('username', $username);
        $this->view->set('hash', $this->user->hash);
        $this->view->set('level', $this->user->isPrivileged() ? 'admin' : 'user');

        return True;
    }

    public function index() {
        if( !$this->initChat() ) {
            return;
        }

        try {
            $messages = '';

            $this->session->rewind();
            foreach( $this->session->readLast() as $chunk ) {

                if( sizeof($chunk) !== sizeof($this->session->fields) ) {
                    continue;
                }

                $message = new \Template('home/message');
                foreach( $this->session->fields as $idx => $field ) {
                    $message->set($field, $chunk[$idx]);
                }

                $messages .= $message->output();
            }

            $this->view->set('messages', $messages);

        } catch( \Exception $error ) {
            $this->view->set('error', $error->getMessage(), True);
            $this->view->set('messages', $error->getMessage());
        }

        $this->view->render();
    }

    public function send() {
        if( !$this->initChat() ) {
            return;
        }

        if( isset($_POST['message']) && !empty($_POST['message']) ) {
            try {
                $this->user->sendMessage($_POST['message']);

            } catch( \Exception $error ) {
                $this->view->set('error', $error->getMessage(), True);

            }
        }

        $this->index();
    }

    public function logout() {
        \Session::getInstance()->destroy();
        $this->redirect('/?c=Key');
    }
}