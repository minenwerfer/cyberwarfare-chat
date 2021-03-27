<?php

namespace Controller;

class Home extends Controller {
    protected $session;
    protected $user;

    public function __construct() {
        [
            $room,
            $key,
            $username,
            $password

        ] = \Session::getInstance()->get();

        $this->session = new \Chat\Session($key, "/tmp/$room");

        $this->user = new \Chat\User($this->session);
        $this->user->auth($username, $password);

        $this->view = new \View\Home;
        $this->view->set('room', $room);
        $this->view->set('username', $username);
        $this->view->set('hash', $this->user->hash);
    }

    public function index() {
        if( !\Session::getInstance()->cookiesSet() ) {
            header('Location: /?c=Key');
        }

        try {
            $messages = '';

            $this->session->rewind();
            foreach( $this->session->readLast() as $chunk ) {
                [
                    $name,
                    $hash,
                    $content,
                    $date

                ] = $chunk;

                $messages .= "$name:$hash em $date - $content<br/>";
            }

            $this->view->set('messages', $messages);

        } catch( \Exception $error ) {
            $this->view->set('error', $error->getMessage(), True);
            $this->view->set('messages', $error->getMessage());
        }

        $this->view->render();
    }

    public function send() {
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
        foreach( \Session::getInstance()->auth_fields as $field ) {
            unset($_COOKIE[$field]);
        }

        header('Location: /?c=Key');
    }
}